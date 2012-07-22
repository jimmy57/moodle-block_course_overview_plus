<?php

// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.
class block_course_overview_plus_edit_form extends block_edit_form {
    protected function specific_definition($mform) {
        // Fields for editing HTML block title and contents.
        $mform->addElement('header', 'configheader', get_string('categorycoursefilter', 'block_course_overview_plus'));
        $mform->addElement('advcheckbox', 'config_categorycoursefilter', get_string('categorycoursefilter', 'block_course_overview_plus'));
        $mform->addElement('header', 'configheader', get_string('teachercoursefilter', 'block_course_overview_plus'));
        $mform->addElement('advcheckbox', 'config_teachercoursefilter', get_string('teachercoursefilter', 'block_course_overview_plus'));
        $mform->addElement('header', 'configheader', get_string('yearcoursefilter', 'block_course_overview_plus'));
        $mform->addElement('advcheckbox', 'config_yearcoursefilter', get_string('yearcoursefilter', 'block_course_overview_plus'));
        for ($i=1; $i<=12; $i++) {
          $months[$i] = userdate(gmmktime(12,0,0,$i,15,2000), "%B");
        }
        $mform->addElement('select', 'config_academicyearstartmonth', get_string('academicyearstart', 'block_course_overview_plus'), $months);
        $mform->addElement('advcheckbox', 'config_defaultyear', get_string('defaultyear', 'block_course_overview_plus'));
        $mform->disabledIf('config_academicyearstartmonth', 'config_yearcoursefilter'); 
        $mform->disabledIf('config_defaultyear', 'config_yearcoursefilter'); 
    }
}
