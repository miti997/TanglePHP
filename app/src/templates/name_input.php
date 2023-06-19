<div>
    <input type="text" @bind:=name>
    <div>Hello {{$name}}</div>
    @each $names as $name :
        @if $name == 'Jhon' :
            {{$name}}
        @elif $name == 'Andrew' :
            <div style="background-color: green;">{{$name}}</div>
        @else :
            <div>{{$name}}</div>
        @end
    @end
</div>