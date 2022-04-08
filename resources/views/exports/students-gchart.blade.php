<table>
    <thead>
    <tr>
        <th>Código</th>
        <th>Pseudónimo</th>
        <th>Facultad</th>
        <th>Semestre</th>
        <th>Nivel</th>
        <th>Bloque</th>
        <th>Duración</th>
        <th>PPM</th>
        <th>Puntos</th>
        <th>Puntos extra de PPM</th>
        <th>Puntuación Total</th>
        <th>Fecha de creación</th>
    </tr>
    </thead>
    <tbody>
    @foreach($students as $student)
        @foreach($student->logs as $log)
        <tr>
            <td>{{ $student->enrollment_code }}</td>
            <td>{{ $student->nickname }}</td>
            <td>{{ $student->school->school }}</td>
            <td>{{ $student->semester }}</td>
            <td>{{ $log->level_id }}</td>
            <td>{{ $log->block_id }}</td>
            <td>{{ $log->duration }}</td>
            <td>{{ $log->ppm }}</td>
            <td>{{ round(count(explode(',',$log->correct_questions_id)) * (20/count($log->problem->questions)),1) }}</td>
            <td>{{ $log->ppm_points }}</td>
            <td>{{ round($log->score,1) }}</td>
            <td>{{ $log->created_at }}</td>
        </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
