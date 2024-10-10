<h1>Student Registration Request</h1>
<p>A new student has requested registration with the following details:</p>
<ul>
    <li><h2>Name: {{ $studentData['name'] }}</li>
    <li><h2>Email: {{ $studentData['email'] }}</li>
</ul>
<h2>  To approve this registration, click <a href="{{ route('admin.approve-student', [$studentID,$adminToken]) }}"> here</a>.</h2>
<h2>To reject this registration, click <a href="{{ route('admin.reject-student', [$studentID, $adminToken]) }}">here</a>.</h2>
