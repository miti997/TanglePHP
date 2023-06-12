<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>"><div>
    <input type="text" x-identifier="<?php echo $identifier?>__0" value="<?php echo $name?>">
    <div>Hello <?php echo htmlspecialchars($name)?></div>
</div><script>bind('<?php echo $identifier?>__0','<?php echo $identifier?>','NameInput','name');</script></div>