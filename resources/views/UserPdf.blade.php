<!DOCTYPE html>
<html>
<head>
    <title>Daftar User</title>
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
    <h1>Export Ke PDF Tabel User</h1>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Email</th>
                <th>No Telepon</th>
                <th>Alamat</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($data as $key => $dt)
                <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $dt->name }}</td>
                    <td>{{ $dt->email }}</td>
                    <td>{{ $dt->no_telepon }}</td>
                    <td>{{ $dt->alamat }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>