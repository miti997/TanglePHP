<input type="text" @bind:=name>

<?php if ({@name@} !== '') {?>
    <div>{{name}}</div>
    <button @on:click=addName>Add</button>
<?php }?>

<?php foreach ({@names@} as $name) {?>
    <div><?=$name?></div>
<?php }?>