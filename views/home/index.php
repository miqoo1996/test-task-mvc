<?php

/**
 * @var $this \App\Core\View\View
 */

use App\Core\Request\Request;

?>

<h1>Test Task</h1>

<hr />

<p>
    Last saved text was:
    <i id="last-saved-text"><?php echo htmlentities($this->lastTextData['value'] ?? ' - '); ?></i>
</p>

<form id="save-text" method="post" action="">
    <input type="hidden" name="csrf_token" value="<?php echo Request::getCsrfToken(); ?>">

    <div class="form-group">
        <label class="control-label"></label>
        <textarea class="form-control" name="value" rows="10" placeholder="Enter your text here..."></textarea>
    </div>

    <div class="form-group" style="float: right; margin-top: 20px;">
        <button type="submit" class="btn btn-lg btn-primary">Save</button>
    </div>

    <div class="clearfix"></div>
</form>