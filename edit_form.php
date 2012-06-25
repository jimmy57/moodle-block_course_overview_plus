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
        $mform->addElement('header', 'configheader', get_string('blocksettings', 'block'));
        $mform->addElement('checkbox', 'config_categorycoursefilter', get_string('categorycoursefilter', 'block_course_overview_plus'));
        $mform->addElement('checkbox', 'config_yearcoursefilter', get_string('yearcoursefilter', 'block_course_overview_plus'));
        $mform->addElement('checkbox', 'config_teachercoursefilter', get_string('teachercoursefilter', 'block_course_overview_plus'));
    }
}
