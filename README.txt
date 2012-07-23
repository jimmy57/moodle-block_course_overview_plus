Installation
------------

1. Place the course_overview_plus folder in the blocks folder and check permissions to make sure your webserver can read the files.

To replace the standard course_overview block on your MyMoodle page
-------------------------------------------------------------------

1. Select 'customise this page' in My Moodle and delete the Course Overview block.
2. Add the Course Overview Plus block.
3. This is unlikely to appear in the correct place, move the block to the second slot down in the main column.

To replace the standard course_overview block across your site (admins)
----------------------------------------------------------------------

1. Hide the existing course_overview block in Settings->Site Administration->Plugins->Blocks->Manage Blocks 
   (note that removing the block from the default MyMoodle will not remove it from users who have customised their page)
2. Add the block to your front page (when on the front page use Settings->Front Page Settings->Turn Editing On and then select 
   Course Overview Plus in the Add a Block block)
3. Move to the main column (it will not display well in a thin column)
4. Edit the configuration using the configuration button at the top of the block and set the Page Contexts to be 'Display throughout the entire site'
   and Visible to No in the On This Page region.
5. Head to your MyMoodle page and in the configuration set Display on Page Types to My Home Page.

This is the official Moodle method and has a slight side effect in that it will appear on all MyMoodle pages including block configuration screens. 
An alternative I use is to rename all instances of course_overview_plus to course_overview and overwrite the existing block, but this will have to be
done every time you upgrade Moodle.


To add the example course filters
---------------------------------

1. Select 'customise this page' in My Moodle.
2. Select the configuration button at the top of the Course Overview Plus block.
3. Tick the checkboxes to display each course filter at the top of the block, you can display more than one at once.

Resetting the year course filter across the site, so that everyone gets newly calculated default year
-----------------------------------------------------------------------------------------------------

1. Run the following SQL where <prefix> is usually mdl

DELETE FROM <prefix>_user_preferences WHERE name = 'courseoverviewplusselectedyear'

Problems?
--------

You can email me at der_andrew_james@yahoo.co.uk

As well as the example course filters included in the block, I am also able to extend the block to allow filtering of the course list by other criteria 
(http://moodle.org/mod/forum/discuss.php?d=184870&parent=805019) or provide help for developers who wish to do this.

Release Notes
-------------

2012031200	Fixed misnnamed variable ($contract) causing PHP warning
		Added German translation and tidier code from Olexs's fork	
2012032000	French translation
2012062500	Course filters added
2012062501	Forum issue fixed
2012062600	Specialization method added
2012070700	Fixed reported bugs
2012072200	Improved year filter, improved handling when filter selections become redundant
