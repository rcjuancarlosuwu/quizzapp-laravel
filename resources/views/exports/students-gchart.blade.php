<table>
    <thead>
    <tr>
        <th>Código</th>
        <th>Pseudónimo</th>
        <th>Facultad</th>
        <th>Semestre</th>
        <th>Promedio de Puntuación</th>
        <th>Promedio de PPM</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        <tr>
            <td>{{ $student->enrollment_code }}</td>
            <td>{{ $student->nickname }}</td>
            <td>{{ $student->school->school }}</td>
            <td>{{ $student->semester }}</td>
            <td>{{ $student->score }}</td>
            <td>{{ $student->ppm }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
