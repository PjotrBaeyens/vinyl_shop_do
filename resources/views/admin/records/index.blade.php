<h1>List of all bruh moments</h1>

<ul>
    <?php
    foreach ($records as $record){
        echo "<li> $record </li>";
    }
    echo "<li>------------------------------------------</li>"
    ?>

    @foreach($records ?? '' as $key => $record)
        <li>Record {{$key}} = {{$record}}</li>
        @endforeach
</ul>
