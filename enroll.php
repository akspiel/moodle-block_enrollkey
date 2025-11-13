<?php
require_once('../../config.php');
require_once($CFG->dirroot . '/group/lib.php'); // Include the groups API library
require_login();

$enroll_key = required_param('enroll_key', PARAM_RAW); // Get the enrollment key from the form

// Query the DB to find the course and self-enrollment instance with the matching key
$instance = $DB->get_record_sql("
    SELECT e.id, e.courseid, e.roleid, e.customint1 AS groupid, e.customint3 AS maxenrolled, e.customint6 AS usegroupkey, c.fullname
    FROM {enrol} e 
    JOIN {course} c ON c.id = e.courseid 
    WHERE e.enrol = 'self' AND e.password = :enroll_key", ['enroll_key' => $enroll_key]);

if ($instance) {
    $enrol = enrol_get_plugin('self');
    $enrol_instance = $DB->get_record('enrol', ['id' => $instance->id]);

    if ($enrol_instance) {
        // Check if the maximum number of enrollments has been reached
        $current_enrollments = $DB->count_records('user_enrolments', ['enrolid' => $instance->id]);

        if ($instance->maxenrolled > 0 && $current_enrollments >= $instance->maxenrolled) {
            // Maximum number of enrollments reached, show an error message
            redirect(new moodle_url('/my'), get_string('maxenrolledreached', 'block_enrollkey'), null, \core\output\notification::NOTIFY_ERROR);
        } else {
            // Enroll the user with the specified role
            $enrol->enrol_user($enrol_instance, $USER->id, $instance->roleid);

            // Check if group enrollment key usage is enabled for this instance
            if ($instance->usegroupkey) {
                // Query to check if the enrollment key matches a group's enrollment key in the course
                $group = $DB->get_record_sql("
                    SELECT g.id 
                    FROM {groups} g 
                    WHERE g.courseid = :courseid AND g.enrolmentkey = :enroll_key", 
                    ['courseid' => $instance->courseid, 'enroll_key' => $enroll_key]);

                // If a group is found, add the user to the group
                if ($group) {
                    groups_add_member($group->id, $USER->id); // Add user to the group
                } else {
                    // Debugging message if the group key does not match any group
                    debugging("Group enrollment key does not match any group in course {$instance->courseid}", DEBUG_DEVELOPER);
                }
            }

            // Redirect the user to the course page after successful enrollment
            redirect(new moodle_url('/course/view.php', ['id' => $instance->courseid]), get_string('enrolled', 'block_enrollkey', $instance->fullname));
        }
    }
} else {
    // If the key is invalid, redirect back with an error
    redirect(new moodle_url('/my'), get_string('invalidkey', 'block_enrollkey'), null, \core\output\notification::NOTIFY_ERROR);
}