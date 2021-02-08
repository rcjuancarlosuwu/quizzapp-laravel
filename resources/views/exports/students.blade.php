<table>
    <thead>
    <tr>
        <th>Código</th>
        <th>Pseudónimo</th>
        <th>Correo</th>
        <th>Facultad</th>
        <th>Semestre</th>
        <th>Fecha de registro</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->enrollment_code }}</td>
            <td>{{ $student->nickname }}</td>
            <td>{{ $student->email }}</td>
            <td>{{ $student->school->school }}</td>
            <td>{{ $student->semester }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
