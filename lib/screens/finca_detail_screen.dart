import 'package:flutter/material.dart';
import '../models/local_models.dart';
import '../screens/capture_screen.dart';
import 'package:provider/provider.dart';
import '../providers/fincas_provider.dart';

class FincaDetailScreen extends StatefulWidget {
  final FincaLocal finca;

  const FincaDetailScreen({super.key, required this.finca});

  @override
  State<FincaDetailScreen> createState() => _FincaDetailScreenState();
}

class _FincaDetailScreenState extends State<FincaDetailScreen> {
  @override
  void initState() {
    super.initState();
    context.read<FincasProvider>().loadLotes(widget.finca.remoteId);
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: Text(widget.finca.nombre),
      ),
      body: Consumer<FincasProvider>(
        builder: (context, provider, _) {
          if (provider.isLoading && provider.lotes.isEmpty) {
            return const Center(child: CircularProgressIndicator());
          }

          final lotes = provider.getLotesForFinca(widget.finca.remoteId);

          if (lotes.isEmpty) {
            return const Center(
              child: Text('Esta finca no tiene lotes registrados'),
            );
          }

          return ListView.builder(
            padding: const EdgeInsets.all(16),
            itemCount: lotes.length,
            itemBuilder: (context, index) {
              final lote = lotes[index];
              return _LoteCard(lote: lote, finca: widget.finca);
            },
          );
        },
      ),
      floatingActionButton: FloatingActionButton.extended(
        onPressed: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => CaptureScreen(
                fincaId: widget.finca.remoteId,
                fincaNombre: widget.finca.nombre,
              ),
            ),
          );
        },
        icon: const Icon(Icons.add_a_photo),
        label: const Text('Capturar'),
      ),
    );
  }
}

class _LoteCard extends StatelessWidget {
  final LoteLocal lote;
  final FincaLocal finca;

  const _LoteCard({required this.lote, required this.finca});

  @override
  Widget build(BuildContext context) {
    return Card(
      margin: const EdgeInsets.only(bottom: 12),
      child: ListTile(
        leading: const Icon(Icons.grid_on, size: 40, color: Colors.blue),
        title: Text(lote.nombre),
        subtitle: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            if (lote.tipoCultivo != null)
              Text('Cultivo: ${lote.tipoCultivo}'),
            if (lote.variedad != null)
              Text('Variedad: ${lote.variedad}'),
            if (lote.hectareas != null)
              Text('${lote.hectareas} hectareas'),
          ],
        ),
        trailing: const Icon(Icons.chevron_right),
        onTap: () {
          Navigator.push(
            context,
            MaterialPageRoute(
              builder: (_) => CaptureScreen(
                fincaId: finca.remoteId,
                fincaNombre: finca.nombre,
                loteId: lote.remoteId,
                loteNombre: lote.nombre,
              ),
            ),
          );
        },
      ),
    );
  }
}
