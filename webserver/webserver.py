from __future__ import print_function
from flask import Flask, render_template, current_app, request
from flask import json, jsonify, make_response, session, send_from_directory
from flask import redirect, url_for, escape, make_response
from flask_bootstrap import Bootstrap
from werkzeug.security import generate_password_hash, check_password_hash
from StringIO import StringIO
import sqlite3
import json
import time
import datetime
import pytz
import requests
import numpy as np
import pandas as pd
import re



##############################################################################
##Constants

#link to primary antibodies spreadsheet
primary_link = 'https://docs.google.com/spreadsheets/d/1hRSs1FOcUqOsDVK0r6v' + \
                'ESSkVmDuapWq8ULgMv_F9EVs/export?gid=0&format=csv'

#link to secondary antibodies spreadsheet
secondary_link = 'https://docs.google.com/spreadsheets/d/1hRSs1FOcUqOsDVK0' + \
                'r6vESSkVmDuapWq8ULgMv_F9EVs/export?gid=659925972&format=csv'

table_class = "table table-striped table-bordered table-hover"
##############################################################################


#Prepare Flask
app = Flask(__name__)

#add celery constants
app.config['CELERY_BROKER_URL'] = 'redis://localhost:6379/0'
app.config['CELERY_RESULT_BACKEND'] = 'redis://localhost:6379/0'

#setup bootstrap
Bootstrap(app)
app.jinja_env.trim_blocks = True
app.jinja_env.lstrip_blocks = True

#turn off truncation of pandas
pd.set_option('display.max_colwidth', -1)

##############################################################################
#Error handlers
##############################################################################

@app.errorhandler(404)
def page_not_found(error, errormessage=""):
    return render_template('error404.html', message=errormessage), 404


@app.errorhandler(500)
def server_problem(error):
    return render_template('error500.html', message=error), 500

##############################################################################
#Helpers

def createCheckboxHTML(names):
    '''
    Function takes in a list of names and creates the necessary html
    for a checkbox for each of the values as the names 

    '''
    checkstr_start = ['<input type="checkbox" name="chex[]" value = "'] * len(names)
    checkstr_end = ['">'] * len(names)
    return [start + middle + end for start, middle, end in zip(checkstr_start, names, checkstr_end)]

#fix to be faster
#fix so that no duplicates
def downloadSpreadSheet(link, checks = False):
    '''
    Downloads a google spreadsheet link and reads it into a panadas dataframe, along with
    an addiontal column of checkboxes that each have the 'name' as the index of the dataframe
    '''
    #grab the data
    r = requests.get(link)
    spread_data = r.content

    #read into pands
    data = pd.read_csv(StringIO(spread_data), index_col = 0)
    del data.index.name

    if checks:
        #add row for checkboxes
        data['Will Use'] = pd.Series(createCheckboxHTML(data.index.tolist()), index = data.index)

    return data
    



##############################################################################
#working on


@app.route('/')
def index():
    print("here")
    return render_template('index.html', params = {})

@app.route('/StandardPlanner')
def StandardPlanner():
    params = {}
    return render_template('StandardPlanner.html', params=params)

@app.route('/MultiplexedPlanner')
def MultiplexedPlanner():
    params = {}
    data = {}

    data["primary"] = re.sub(table_class, table_class + '" id = "primarytable"', 
                        downloadSpreadSheet(primary_link, checks = True).to_html(classes = table_class, escape = False))

    data["secondary"] = re.sub(table_class, table_class + '" id = "secondarytable"',
                        downloadSpreadSheet(secondary_link, checks = True).to_html(classes = table_class, escape = False))


    return render_template('MultiplexedPlanner.html', params=params, data = data)

    ProduceAntibody
@app.route('/ProduceAntibody')
def ProduceAntibody():
    params = {}
    data = {}
    nsolution =  int(request.args.get('nsolution'))
    sol_lens = json.loads(request.args.get('rowlens'))
    secs = []
    prims = []
    rep_num =  request.args.get('ReplicationNum')
    wellsize =  request.args.get('wellsize')
    vol_per_well =  request.args.get('VolumePerWell')

    total_vol = int(rep_num) * float(vol_per_well)
    for i in range(1, nsolution + 1):
        for j in range(1, int(sol_lens[i-1]) + 1):
            colorname = "colors" + str(j) + "_" + str(i)
            primaryname = "primary" + str(j) + "_" + str(i)
            secondaryname = "secondary" + str(j) + "_" + str(i)
            secs.append(str(request.args.get(secondaryname)))
            prims.append(str(request.args.get(primaryname)))
            # print(request.args.get(colorname))
            # print(request.args.get(primaryname))
            # print(request.args.get(secondaryname))
    # print(secs)
    # print(prims)
    primary_data = downloadSpreadSheet(primary_link)
    secondary_data = downloadSpreadSheet(secondary_link)
    prim_dilut = primary_data.ix[prims]['Dilution']
    sec_dilut = secondary_data.ix[secs]['Dilution']
    output = str(rep_num) + " Replications with " + str(vol_per_well) + "ul per Well"
    ind = 0
    for i in range(1, nsolution + 1):
        output += "Solution " + str(i) + ":<br><br>"
        summed = 0
        for j in range(1, int(sol_lens[i-1]) + 1):
            if  sec_dilut.index[ind] != "None":
                output += prim_dilut.index[ind] + ": " + str(total_vol/float(prim_dilut.values[ind])) +"ul<br>"
                summed += total_vol/float(prim_dilut.values[ind])
                # print(sec_dilut.values[ind])
            ind += 1

        output += "base: "+ str(total_vol - summed)+ "ul<br><br>"
    params['output'] = output

    
    return render_template('displaysol.html', params=params, data = data)

@app.route('/GenerateAntibody')
def GenerateAntibody():
    params = {}
    data = {}

    #get parameters from form
    rep_num =  request.args.get('ReplicationNum')
    wellsize =  request.args.get('wellsize')
    vol_per_well =  request.args.get('VolumePerWell')
    chex = request.args.getlist('chex[]')

    #separate the checks for primary and secondary
    secondarys = [check for check in chex if 'anti' in check]
    primarys = [check for check in chex if 'anti' not in check]

    #condense the data to what we chose
    primary_data = downloadSpreadSheet(primary_link)
    secondary_data = downloadSpreadSheet(secondary_link)
    primary_subset = primary_data.ix[primarys]
    secondary_subset = secondary_data.ix[secondarys]

    #two series to add to our primary data
    possible_secondaries = pd.Series(0, index = primary_subset.index)
    possible_colors = pd.Series(0, index = primary_subset.index)
    for primary in primary_subset.index:
        host  = primary_subset.ix[primary, 'Host']
        secs_anti_host = secondary_subset[secondary_subset['Antibody Against'] == host]
        possible_secondaries.ix[primary] = ",<br><br>".join(secs_anti_host.index.tolist())
        possible_colors.ix[primary] = str(list(secs_anti_host['Excitation'].values))

    primary_subset.insert(0, "Possible Secondaries", possible_secondaries)
    primary_subset.insert(1, "Possible Colors", possible_colors)



    data["primary"] = re.sub(table_class, table_class + '" id = "primarytable"',  primary_subset.to_html(classes = table_class, escape = False))
    data["secondary"] = re.sub(table_class, table_class + '" id = "secondarytable"', secondary_subset.to_html(classes = table_class, escape = False))
    data["primary_json"] = primary_subset.to_json(orient='index')
    data["secondary_json"] = secondary_subset.to_json(orient='index')
    data["primary_hosts"] = list(set(primary_subset['Host']))
    data["secondary_hosts"] = list(set(secondary_subset['Host']))
    data["secondary_colors"] = list(set(secondary_subset['Excitation'].map(str)))
    data["primary_names"] = list(primary_subset.index)
    data["secondary_names"] = list(secondary_subset.index)
    data['wellsize'] = wellsize
    data['rep_num'] = rep_num
    data['vol_per_well'] = vol_per_well

    return render_template('GenerateAntibody.html', params=params, data = data)


##############################################################################

if __name__ == '__main__':
    app.debug = True
    app.run(host='0.0.0.0')




