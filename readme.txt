Name:		apcupsd query 
Created by:	BSOD2600
Version:	1.0	
Cacti:		0.8.6j + patches
Summary:	This script is designed to run against a host running the APC Daemon, Apcupsd,
		with the NIS (Network Information Server) feature enabled.  Furthermore, it
		utlizes the 'apcaccess status' command to remotely query a APC status.  This 
		is done primarely for the older APC devices which do not support SNMP.  The
		script will still work for the SNMP enabled devices, but will be a little slower.
	
Changelog:
-1.1
Fixed APC Battery template due to cacti import bug
Exported with cacti 0.8.7a

-1.0
Initial release
Exported with cacti 0.8.6j


			
Installation:
1) Copy the query_ script to your /cacti/scripts/ directory
2) Modify the query_ script and change the $APCACCESS_PATH variable to the location of apcaccess on your system
3) Test the script to make sure it works.  Run: php query_apcupsd myserver 3551.  You should see output like: LINEV:122.0 LOADPCT:47.0 BCHARGE:100.0 TIMELEFT:15.6 BATTV:27.1 TONBATT:0 NOMINV:120 NOMBATTV:24.0.  This will vary, depending on what fields are supported by the UPS.
4) In Cacti, add the APC UPS Statistics data input method.  
5) In Graph Management, add the APC Battery Statistics and APC Line Statistics templates.  Fill in the various fields from the dropdown boxes.
6) Wait 10 minutes for the graphs to appear!


Additional Information:
I designed the script and templates so they could easily be extended.  If there are additional fields which 'apcaccess status' returns, which you want to graph, then one just needs to edit the php script.  In the apcupsd_parse function, add additional elseif statements to the "Advanced APC parameters" portion.  Veryify the script properly spits out the new data.  Then in Cacti, edit the APC UPS Statistics Data Input Method. Add the additional field(s) you added to the php script to the Output Fields portion.  Next, modify the APC UPS Statistics Data Template.  Add the new Data sources for each of the new field(s) you previously added.  Make sure to select the correct Output Field from the dropdown list and set a rational Min/Max Value.  Lastly, edit an APC graph template and add the additional field(s).