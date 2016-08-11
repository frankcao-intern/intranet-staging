<html>
<head>
	<title>PoC of Retail System!</title>
</head>

<body>

<?php echo form_open('poc_retail/process_input'); ?>
	<?php echo "US Only : "; ?>
    <?php echo form_checkbox('optionType1','yes',FALSE); ?>
    <?php echo nl2br ("\n"); ?>
    <?php echo "Critical : "; ?>
    <?php echo form_checkbox('optionType2','yes',FALSE); ?>
    <?php echo nl2br ("\n"); ?>
    <?php echo form_label('Task Name : ', 'u_taskname'); 
    		$data= array(
    		'type' => 'task',
    		'name' => 'u_taskname',
    		'placeholder' => 'Enter the task here!',
    		'class' => 'input_box'
    		);
    		echo form_input($data);
    ?>
    <?php echo nl2br ("\n"); ?>
    <?php echo form_label('Task Desc : ', 'u_taskdesc'); 
    		$data= array(
    		'type' => 'task',
    		'name' => 'u_taskdesc',
    		'placeholder' => 'Enter the task here!',
    		'class' => 'input_box'
    		);
    		echo form_input($data);
    ?>

    <?php echo form_submit('mysubmit', 'Submit'); ?>

<?php echo form_close(); ?>

<?php echo form_open('poc_retail/show_tasks'); ?>
    <?php echo form_submit('show_tasks', 'Show Tasks'); ?>

<?php echo form_close(); ?>

<?php echo form_open('poc_retail/delete_tasks'); ?>
    <?php echo form_submit('delete_tasks', 'Delete Tasks'); ?>

<?php echo form_close(); ?>

<?php echo nl2br ("<------TESTING----->\n\n"); ?>
<?php echo nl2br("Recently Entered Task:\n\n"); ?>

<?php
	if (isset($o1)) {
		echo "US Only:";
		if ($o1 == "yes") {
			echo "True ";
		} else {
			echo "False ";
		}
	} ?>
	
<?php
	if (isset($o2)) {
		echo "| Critical:";
		if ($o2 == "yes") {
			echo "True";
		} else {
			echo "False";
		}
	} ?>

<?php
	if (isset($entry_count)) {
		echo nl2br ("\nTask Count : ");
		echo $entry_count;
	} ?>

<?php
	if (isset($title)) {
		echo nl2br ("\nTask Name : ");
		echo $title;
	} ?>

<?php
	if (isset($desc)) {
		echo nl2br ("\nTask Desc : ");
		echo $desc;
	}?>

</body>

</html>