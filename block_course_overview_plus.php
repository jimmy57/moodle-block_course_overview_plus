<?php

/**
 * Course overview block plus
 *
 * A copy of the course overview block with the option to hide courses and course information
 *
 * @package   blocks
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once($CFG->dirroot.'/lib/weblib.php');
require_once($CFG->dirroot.'/lib/formslib.php');
require_once($CFG->dirroot.'/course/lib.php');

class block_course_overview_plus extends block_base {
    /**
     * block initializations
     */
    public function init() {
        $this->title   = get_string('pluginname', 'block_course_overview_plus');
    }

    /**
     * block contents
     *
     * @return object
     */
    public function get_content() {
    global $USER, $CFG, $DB, $PAGE, $OUTPUT;

    if($this->content !== NULL) {
      return $this->content;
    }
    $hidecourse          = optional_param('hidecourse', 0, PARAM_INT);
    $showcourse          = optional_param('showcourse', 0, PARAM_INT);
    $expandcourseinfo    = optional_param('expandcourseinfo', 0, PARAM_INT);
    $contractcourseinfo  = optional_param('contractcourseinfo', 0, PARAM_INT);
    $managehiddencourses = optional_param('managehiddencourses', 0, PARAM_INT);
    //need to keep track of latest changes as user preferences are not reliable
    $recenthide = 0;
    $recentshow=0;
    $recentexpand = 0;
    $recentcontract=0;

    if ($hidecourse != 0) {
        set_user_preference('courseoverviewplushide'.$hidecourse, 1, $USER->id);
        $recenthide=$hidecourse;
    }

    if ($showcourse != 0 && isset($user)) {
        set_user_preference('courseoverviewplushide'.$showcourse, 0, $USER->id);
        $recentshow=$showcourse;
    }

    if ($expandcourseinfo != 0) {
        set_user_preference('courseoverviewpluscontract'.$expand, 0, $USER->id);
        $recentexpand=$expandcourseinfo;
    }

    if ($contractcourseinfo != 0) {
        set_user_preference('courseoverviewpluscontract'.$contract, 1, $USER->id); 
        $recentcontract=$contractcourseinfo;
    }

    $this->content = new stdClass();
    $this->content->text = '';
    $this->content->footer = '';

    $content = array();

    // limits the number of courses showing up
    $courses_limit = 21;
    // FIXME: this should be a block setting, rather than a global setting
    if (isset($CFG->mycoursesperpage)) {
        $courses_limit = $CFG->mycoursesperpage;
    }

    $morecourses = false;
    if ($courses_limit > 0) {
        $courses_limit = $courses_limit + 1;
    }

    $courses = enrol_get_my_courses('id, shortname, modinfo', 'fullname ASC', $courses_limit);
    $site = get_site();
    $course = $site; //just in case we need the old global $course hack

	if (is_enabled_auth('mnet')) {
        $remote_courses = get_my_remotecourses();
    }

    if (empty($remote_courses)) {
        $remote_courses = array();
    }

    if (($courses_limit > 0) && (count($courses) >= $courses_limit)) {
        // get rid of any remote courses that are above the limit
        $remote_courses = array_slice($remote_courses, 0, $courses_limit - count($courses), true);
        if (count($courses) >= $courses_limit) {
            //remove the 'marker' course that we retrieve just to see if we have more than $courses_limit
            array_pop($courses);
            }
            $morecourses = true;
        }

        if (array_key_exists($site->id,$courses)) {
            unset($courses[$site->id]);
        }
		
        $collapsible = ' ';
		$courseslist = ' ';
         
        echo '<style type="text/css">';
        echo '.hidden {display:none;}';
        echo '</style>';
		
		if (empty($courses)) {
            $content[] = get_string('nocourses','my');
        } else {
            ob_start();

            $htmlarray = array();
            if ($modules = $DB->get_records('modules')) {
            foreach ($modules as $mod) {
                if (file_exists($CFG->dirroot.'/mod/'.$mod->name.'/lib.php')) {
                    include_once($CFG->dirroot.'/mod/'.$mod->name.'/lib.php');
                    $fname = $mod->name.'_print_overview';
                    if (function_exists($fname)) {
                        $fname($courses,$htmlarray);
                    }
                }
            }
        }
		
		$hidden = 0;
        foreach ($courses as $c) {
            user_preference_allow_ajax_update('courseoverviewplushide'.$c->id, PARAM_INT);
            user_preference_allow_ajax_update('courseoverviewpluscontract'.$c->id, PARAM_INT);

            if ($c->id==$recenthide) {
                $hidethiscourse = 1;
            } elseif ($c->id==$recentshow) {
                $hidethiscourse = 0;
            } else {
                $hidethiscourse = get_user_preferences('courseoverviewplushide'.$c->id, 0);
            }

            if ($c->id==$recentcontract) {
                $contractthiscourse = 1;
            } elseif ($c->id==$recentexpand) {
                $contractthiscourse = 0;
            } else {
                $contractthiscourse = get_user_preferences('courseoverviewpluscontract'.$c->id, 0);
            }

//            if (isset($USER->lastcourseaccess[$c->id])) {
//               $courses[$c->id]->lastaccess = $USER->lastcourseaccess[$c->id];
//            } else {
//                $courses[$c->id]->lastaccess = 0;
//            }

            $courses[$c->id]->hide = $hidethiscourse;
            if($hidethiscourse) {
                $hidden = $hidden+1;
            }
            $courses[$c->id]->infohide = $contractthiscourse;
        }

    
        if ($managehiddencourses == 0) {
            echo get_string('youhave', 'block_course_overview_plus').' <span id="hiddencourses" style="color:darkred;">'.$hidden.'</span> '.get_string(
'hiddencourses', 'block_course_overview_plus').' | <a href="index.php?managehiddencourses=1">'.get_string('managehiddencourses', 
'block_course_overview_plus').'</a>';
        } else {
            echo  ' <a href="index.php">'.get_string('stopmanaginghiddencourses', 'block_course_overview_plus').'</a>';
        }

        foreach ($courses as $c) {
            $courseslist = $courseslist.$c->id.' ';
            if(($c->hide==0)||$managehiddencourses==1){
                echo '<div id="course'.$c->id.'" class="course'.$c->id.'">';
            } else {
                echo '<div id="course'.$c->id.'" class="course'.$c->id.' hidden">';
            }

            echo $OUTPUT->box_start('coursebox');
            $attributes = array('title' => s($c->fullname));
            if (empty($c->visible)) {
                $attributes['class'] = 'dimmed';
            }
 
           if($c->hide==0) {
               echo $OUTPUT->heading(html_writer::link(
                   new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname), $attributes).
                       ' <div style="text-align: right;"><a href="index.php?hidecourse='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" id="hider'.$c->
id.'" title="'.get_string('hidecourse', 'block_course_overview_plus').'">'.
                       '<img src="'.$OUTPUT->pix_url('i/hide') . '" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_plus').'" /></a>'.
                       '<a href="index.php?showcourse='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" id="shower'.$c->id.'" class="hidden" title="'.
get_string('showcourse', 'block_course_overview_plus').'">'.
                       '<img src="'.$OUTPUT->pix_url('i/show') . '" class="icon" alt="'.get_string('showcourse', 'block_course_overview_plus').'" /></a><br 
/></div>', 3);
           } else {
               echo $OUTPUT->heading(html_writer::link(
                   new moodle_url('/course/view.php', array('id' => $c->id)), format_string($c->fullname), $attributes).
                       ' <div style="text-align: right;"><a href="index.php?hidecourse='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" id="hider'.$c->
id.'" class="hidden" title="'.get_string('hidecourse', 'block_course_overview_plus').'">'.
                       '<img src="'.$OUTPUT->pix_url('i/hide') . '" class="icon" alt="'.get_string('hidecourse', 'block_course_overview_plus').'" /></a>'.
                       '<a href="index.php?showcourse='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" id="shower'.$c->id.'" title="'.get_string(
'showcourse', 'block_course_overview_plus').'">'.
                       '<img src="'.$OUTPUT->pix_url('i/show') . '" class="icon" alt="'.get_string('showcourse', 'block_course_overview_plus').'" /></a><br 
/></div>', 3);
           }


          if (array_key_exists($c->id,$htmlarray)) {
              $collapsible = $collapsible.$c->id.' ';
              if ($c->infohide == 0) {
                  echo '<div id="extra'.$c->id.'">';
              } else {
                  echo '<div id="extra'.$c->id.'" class="hidden">';
              }
              foreach ($htmlarray[$c->id] as $modname => $html) {
                  echo $html;
              }
              echo '</div>';

          if ($c->infohide == 0) {
              echo ' <div style="text-align: right;"><a href="index.php?contractcourseinfo='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" 
id="contract'.$c->id.'" title="'.get_string('collapsecourseinfo', 'block_course_overview_plus').'">'.
                     '<img src="'.$OUTPUT->pix_url('i/contract') . '" class="icon" alt="'.get_string('collapsecourseinfo', 'block_course_overview_plus').'" /></a>'.
                     '<a href="index.php?expandcourseinfo='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" id="expand'.$c->id.'" class="hidden" 
title="'.get_string('expandcourseinfo', 'block_course_overview_plus').'">'.
                     '<img src="'.$OUTPUT->pix_url('i/expand') . '" class="icon" alt="'.get_string('expandcourseinfo', 'block_course_overview_plus').'" 
/></a></div>';
          } else {
              echo ' <div style="text-align: right;"><a href="index.php?contractcourseinfo='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" title="'.
get_string('collapsecourseinfo', 'block_course_overview_plus').'"  id="contract'.$c->id.'" class="hidden" >'.
                     '<img src="'.$OUTPUT->pix_url('i/contract') . '" class="icon" alt="'.get_string('collapsecourseinfo', 'block_course_overview_plus').'" /></a>'.
                     '<a href="index.php?expandcourseinfo='.$c->id.'&amp;managehiddencourses='.$managehiddencourses.'" title="'.get_string('expandcourseinfo', 
'block_course_overview_plus').'" id="expand'.$c->id.'" >'.
                     '<img src="'.$OUTPUT->pix_url('i/expand') . '" alt="'.get_string('expandcourseinfo', 'block_course_overview_plus').'" 
class="icon"/></a></div>';
          }
     }
      echo $OUTPUT->box_end();
      echo '</div>';
}
$content[] = ob_get_contents();
ob_end_clean();
}

// if more than 20 courses
if ($morecourses) {
    $content[] = '<br />...';
}

$this->content->text = implode($content);

$PAGE->requires->yui_module('moodle-block_course_overview_plus-collapse', 'M.block_course_overview_plus.initCollapse', array(array('courses'=>trim($collapsible))));
$PAGE->requires->yui_module('moodle-block_course_overview_plus-hide', 'M.block_course_overview_plus.initHide', array(array('courses'=>trim($courseslist),'editing'
=>$managehiddencourses)));

        return $this->content;
    }

    /**
     * allow the block to have a configuration page
     *
     * @return boolean
     */
    public function has_config() {
        return false;
    }

    /**
     * locations where block can be displayed
     *
     * @return array
     */
    public function applicable_formats() {
        return array('my-index'=>true);
    }
}
?>
