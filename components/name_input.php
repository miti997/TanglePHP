<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>"><br>
<div>
    <input type="text" x-identifier="<?php echo $identifier?>__0" value="<?php echo $name?>">

    <?php if($name !== ''){?>
        <div><?php echo htmlspecialchars($name)?></div>
        <button x-identifier="<?php echo $identifier?>__1">Add</button>
    <?php }?>

    <?php foreach($names as $names){?>
        <div><?php echo htmlspecialchars($name)?></div>
    <?php }?>
</div></div>