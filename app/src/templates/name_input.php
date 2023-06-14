<div>
    <input type="text" @bind:=name>
    <div>Hello {{$name}}</div>
    @each $names as $name :@
        <div>{{$name}}</div>
    @:@
</div>