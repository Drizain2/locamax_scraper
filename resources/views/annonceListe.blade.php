<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rental Sources</title>
</head>
<body>
<h1 class="">Résultats de scrapping</h1>
<table border="1" cellpadding="8" style="margin-top:20px">
    <tr>
        <th>Titre</th>
        <th>Type</th>
        <th>Ville</th>
        <th>district</th>
        <th>Phone</th>
        <th>URL</th>
    </tr>
    @foreach ($rentals as $r)
    <tr>
        <td>{{ $r->name_or_title }}</td>
        <td>{{ $r->source_type }}</td>
        <td>{{ $r->city }}</td>
        <td>{{ $r->district }}</td>
        <td>{{ $r->phone_number }}</td>
        <td ><a href="{{ $r->source_url }}" target="_blank">voir</a></td>

    </tr>

    @endforeach
</table>
</body>
</html>
