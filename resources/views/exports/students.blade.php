<table>
    <thead>
    <tr>
        <th>Nombre</th>
        <th>Email</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->name }}</td>
            <td>{{ $student->email }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
