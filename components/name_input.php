<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>"><input type="text" x-identifier="<?php echo $identifier?>__0" value="<?php echo $params['name']?>"><?php if ($params['name'] !== '') {?>    <div><?php echo $params['name']?></div>    <button x-identifier="<?php echo $identifier?>__1">Add</button><?php }?><?php foreach ($params['names'] as $name) {?>    <div><?=$name?></div><?php }?><script>bind('<?php echo $identifier?>__0','<?php echo $identifier?>','NameInput','name');on('click','<?php echo $identifier?>__1','<?php echo $identifier?>','NameInput','addName');</script></div>