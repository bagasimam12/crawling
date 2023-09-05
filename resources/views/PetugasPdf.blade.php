<!DOCTYPE html>
<html>
<head>
    <title>Daftar Petugas</title>
    <style>
        table {
          font-family: arial, sans-serif;
          border-collapse: collapse;
          width: 100%;
        }
        
        td, th {
          border: 1px solid #dddddd;
          text-align: left;
          padding: 8px;
        }
        
        tr:nth-child(even) {
          background-color: #dddddd;
        }
        </style>
</head>
<body>
    <h1>Export Ke PDF Tabel Petugas</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Jabatan</th>
                <th>Jenis Kelamin</th>
                <th>No Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $dt)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->jabatan }}</td>
                    <td>{{ $dt->jenis_kelamin }}</td>
                    <td>{{ $dt->no_telepon }}</td>
                    <td>{{ $dt->alamat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>