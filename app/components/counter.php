<div x-identifier="<?php echo $identifier?>" x-structure="<?php echo htmlspecialchars(json_encode($this->data))?>"><button x-identifier="<?php echo $identifier?>__0">-</button>
<span><?php echo htmlspecialchars($counter)?></span>
<button x-identifier="<?php echo $identifier?>__1">+</button>
<div>Count is at: <?php echo htmlspecialchars($counter)?></div><script>on('click','<?php echo $identifier?>__0','<?php echo $identifier?>','Counter','decrement');on('click','<?php echo $identifier?>__1','<?php echo $identifier?>','Counter','increment');</script></div>