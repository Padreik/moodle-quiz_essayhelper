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

/**
 * @package   quiz_essayhelper
 * @copyright 2016, Philippe Girard <girdphil@gmail.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/mod/quiz/report/default.php');

class quiz_essayhelper_report extends quiz_default_report {
    protected $questions;
    protected $cm;
    protected $quiz;
    protected $course;

    public function display($quiz, $cm, $course) {
        global $CFG, $DB, $PAGE;

        $this->quiz = $quiz;
        $this->cm = $cm;
        $this->course = $course;

        $this->questions = quiz_report_get_significant_questions($quiz);

        // Generate and display the report, or
        // other functionality.
        $this->print_header_and_tabs($cm, $course, $quiz, 'essayhelper');

        $this->display_index();

        return true;
    }

    protected function display_index() {
        global $OUTPUT;

        echo $OUTPUT->heading(get_string('questionsthatneedgrading', 'quiz_grading'), 3);

        $dm = new question_engine_data_mapper();
        $j = new qubaid_join('{quiz_attempts} quiza', 'quiza.uniqueid', "", array());
        print_r($dm->load_questions_usages_question_state_summary($j, array_keys($this->questions)));

        $table = new html_table();
        $table->class = 'generaltable';
        $table->id = 'questionstograde';

        $table->head[] = get_string('qno', 'quiz_grading');
        $table->head[] = get_string('questionname', 'quiz_grading');
        $table->head[] = get_string('tograde', 'quiz_grading');
        $table->head[] = get_string('alreadygraded', 'quiz_grading');
        $table->head[] = get_string('total', 'quiz_grading');

        $table->data = $data;
        echo html_writer::table($table);
    }
}