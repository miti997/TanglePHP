<br>
<div>
    <input type="text" @bind:=name>

    @if $name !== '':@
        <div>{{$name}}</div>
        <button @on:click=addName>Add</button>
    @:@

    @each $names as $names:@
        <div>{{$name}}</div>
    @:@
</div>