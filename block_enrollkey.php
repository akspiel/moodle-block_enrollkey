<?php
class block_enrollkey extends block_base {

    public function init() {
      $this->title = get_string('blocktitle', 'block_enrollkey');
    }

    public function get_content() {
        if ($this->content !== null) {
            return $this->content;
        }

        global $CFG;

        // Create a form with a single input field for enrollment key
        $this->content = new stdClass();
        $this->content->text = '
            <form method="post" action="' . $CFG->wwwroot . '/blocks/enrollkey/enroll.php">
                <input class="form-control" type="text" name="enroll_key" placeholder="Enter enrollment key" required>
                <button class="btn btn-secondary" type="submit">Submit</button>
            </form>
        ';

        return $this->content;
    }

    public function applicable_formats() {
        return ['all' => true];
    }
}
