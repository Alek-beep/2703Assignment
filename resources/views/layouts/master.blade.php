<!DOCTYPE html>
<html>
<head>
    <title>@yield('title')</title>
    <meta charset="utf-8">
    <link href="{{asset('mycss/wp.css')}}" rel="stylesheet">
</head>
<body>
    <header>
        <h1>Work Integrated Learning</h1>
        <ul id="navbar">
            <li><a href="/">Home</a></li>
            <li style="float:right"><a href="/logOut">Log Out</a></li>
            <li style="float:right"><a href="/documentation">Documentation</a></li>
            <li style="float:right"><a href="/projectAssignment">Project Assignment</a></li>
            <li style="float:right"><a href="/top3">Top 3</a></li>
            <li style="float:right"><a href="/advertise">Advertise</a></li>
            <li style="float:right"><a href="/studentsApplied">Applied Students</a></li>
        </ul>
    </header>
    <main>
        @yield('content')
    </main>
</body>
</html>