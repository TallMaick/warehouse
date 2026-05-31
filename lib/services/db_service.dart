import 'package:sqflite/sqflite.dart';
import 'package:path/path.dart';

class DbService {
  static final DbService _instance = DbService._internal();
  factory DbService() => _instance;

  Database? _db;

  DbService._internal();

  Future<Database> get db async {
    if (_db != null) return _db!;
    _db = await _initDb();
    return _db!;
  }

  Future<Database> _initDb() async {
    final dbPath = await getDatabasesPath();
    final path = join(dbPath, 'agrofield.db');

    return openDatabase(
      path,
      version: 1,
      onCreate: (db, version) async {
        await db.execute('''
          CREATE TABLE fincas (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            remoteId INTEGER UNIQUE,
            nombre TEXT,
            latitud TEXT,
            longitud TEXT,
            hectareasTotales TEXT,
            tipoSuelo TEXT,
            estado TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE lotes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            remoteId INTEGER UNIQUE,
            fincaRemoteId INTEGER,
            nombre TEXT,
            hectareas TEXT,
            tipoCultivo TEXT,
            variedad TEXT,
            fechaSiembra TEXT,
            latitud TEXT,
            longitud TEXT
          )
        ''');

        await db.execute('''
          CREATE TABLE media (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            localPath TEXT,
            tipo INTEGER,
            syncStatus INTEGER,
            transcribedText TEXT,
            fincaRemoteId INTEGER,
            loteRemoteId INTEGER,
            actividadRemoteId INTEGER,
            categoria TEXT,
            mimeType TEXT,
            pesoBytes INTEGER,
            remoteFileKey TEXT,
            errorMessage TEXT,
            createdAt INTEGER
          )
        ''');
      },
    );
  }

  Future<void> clearAll() async {
    final database = await db;
    await database.delete('fincas');
    await database.delete('lotes');
    await database.delete('media');
  }

  Future<void> close() async {
    if (_db != null) {
      await _db!.close();
      _db = null;
    }
  }
}
