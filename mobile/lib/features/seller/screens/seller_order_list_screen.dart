import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../providers/seller_order_provider.dart';

class SellerOrderListScreen extends ConsumerWidget {
  const SellerOrderListScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final state = ref.watch(sellerOrderProvider);

    return Scaffold(
      appBar: AppBar(
        leading: IconButton(
          icon: const Icon(Icons.logout),
          onPressed: () => _handleLogout(context, ref),
        ),
        title: const Text("Pesanan Masuk", style: TextStyle(fontWeight: FontWeight.bold)),
        actions: [
          IconButton(
            icon: const Icon(Icons.refresh),
            onPressed: () => ref.read(sellerOrderProvider.notifier).refreshOrders(),
          ),
        ],
      ),
      body: Column(
        children: [
          _buildFilterBar(ref, state.currentFilter),
          Expanded(
            child: state.isLoading 
              ? const Center(child: CircularProgressIndicator())
              : state.orders.isEmpty
                ? const Center(child: Text("Tidak ada pesanan"))
                : ListView.builder(
                    padding: const EdgeInsets.all(16),
                    itemCount: state.orders.length,
                    itemBuilder: (context, index) {
                      final order = state.orders[index];
                      return _OrderCard(order: order);
                    },
                  ),
          ),
        ],
      ),
    );
  }

  Widget _buildFilterBar(WidgetRef ref, String currentFilter) {
    final filters = ['ALL', 'NEW', 'PROCESSING', 'DELIVERING', 'DONE', 'REJECTED'];
    return SizedBox(
      height: 60,
      child: ListView.builder(
        scrollDirection: Axis.horizontal,
        padding: const EdgeInsets.symmetric(horizontal: 16, vertical: 8),
        itemCount: filters.length,
        itemBuilder: (context, index) {
          final f = filters[index];
          return Padding(
            padding: const EdgeInsets.only(right: 8),
            child: ChoiceChip(
              label: Text(f),
              selected: currentFilter == f,
              onSelected: (_) => ref.read(sellerOrderProvider.notifier).setFilter(f),
              selectedColor: Colors.red,
              labelStyle: TextStyle(color: currentFilter == f ? Colors.white : Colors.black),
            ),
          );
        },
      ),
    );
  }
}

class _OrderCard extends ConsumerWidget {
  final dynamic order;
  const _OrderCard({required this.order});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    return Card(
      margin: const EdgeInsets.only(bottom: 16),
      child: Padding(
        padding: const EdgeInsets.all(16.0),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                Text(order['order_number'], style: const TextStyle(fontWeight: FontWeight.bold, fontSize: 16)),
                _StatusBadge(status: order['status']),
              ],
            ),
            const Divider(),
            Text("Pelanggan: ${order['user']['name']}"),
            Text("Alamat: ${order['delivery_address']}", maxLines: 1, overflow: TextOverflow.ellipsis),
            Text("Total: Rp ${order['total_amount']}", style: const TextStyle(color: Colors.red, fontWeight: FontWeight.bold)),
            const SizedBox(height: 12),
            _buildActionButtons(context, ref),
          ],
        ),
      ),
    );
  }

  Widget _buildActionButtons(BuildContext context, WidgetRef ref) {
    final status = order['status'];
    if (status == 'DONE' || status == 'REJECTED') return const SizedBox.shrink();

    return Row(
      mainAxisAlignment: MainAxisAlignment.end,
      children: [
        if (status == 'NEW') ...[
          ElevatedButton(
            onPressed: () => _updateStatus(context, ref, 'REJECTED', needsNote: true),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.grey[200]),
            child: const Text("Tolak", style: TextStyle(color: Colors.black)),
          ),
          const SizedBox(width: 8),
          ElevatedButton(
            onPressed: () => _updateStatus(context, ref, 'PROCESSING'),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.green),
            child: const Text("Terima", style: TextStyle(color: Colors.white)),
          ),
        ],
        if (status == 'PROCESSING')
          ElevatedButton(
            onPressed: () => _updateStatus(context, ref, 'DELIVERING'),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.blue),
            child: const Text("Kirim Pesanan", style: TextStyle(color: Colors.white)),
          ),
        if (status == 'DELIVERING')
          ElevatedButton(
            onPressed: () => _updateStatus(context, ref, 'DONE'),
            style: ElevatedButton.styleFrom(backgroundColor: Colors.red),
            child: const Text("Selesai", style: TextStyle(color: Colors.white)),
          ),
      ],
    );
  }

  void _updateStatus(BuildContext context, WidgetRef ref, String newStatus, {bool needsNote = false}) async {
    String? note;
    if (needsNote) {
      note = await _showNoteDialog(context);
      if (note == null) return;
    }

    final result = await ref.read(sellerOrderProvider.notifier).updateStatus(order['id'], newStatus, note: note);
    if (context.mounted) {
      ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(result['message'])));
    }
  }

  Future<String?> _showNoteDialog(BuildContext context) {
    final controller = TextEditingController();
    return showDialog<String>(
      context: context,
      builder: (context) => AlertDialog(
        title: const Text("Alasan Penolakan"),
        content: TextField(
          controller: controller,
          decoration: const InputDecoration(hintText: "Contoh: Stok ayam habis"),
        ),
        actions: [
          TextButton(onPressed: () => Navigator.pop(context), child: const Text("Batal")),
          TextButton(onPressed: () => Navigator.pop(context, controller.text), child: const Text("Kirim")),
        ],
      ),
    );
  }
}

class _StatusBadge extends StatelessWidget {
  final String status;
  const _StatusBadge({required this.status});

  @override
  Widget build(BuildContext context) {
    Color color = Colors.grey;
    if (status == 'NEW') color = Colors.orange;
    if (status == 'PROCESSING') color = Colors.green;
    if (status == 'DELIVERING') color = Colors.blue;
    if (status == 'DONE') color = Colors.red;
    
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
      decoration: BoxDecoration(color: color, borderRadius: BorderRadius.circular(4)),
      child: Text(status, style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold)),
    );
  }
}
