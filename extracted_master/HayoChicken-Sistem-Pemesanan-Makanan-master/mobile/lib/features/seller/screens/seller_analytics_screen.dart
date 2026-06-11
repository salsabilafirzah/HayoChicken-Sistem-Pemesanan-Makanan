import 'package:flutter/material.dart';
import '../services/analytics_service.dart';

class SellerAnalyticsScreen extends StatefulWidget {
  const SellerAnalyticsScreen({super.key});

  @override
  State<SellerAnalyticsScreen> createState() => _SellerAnalyticsScreenState();
}

class _SellerAnalyticsScreenState extends State<SellerAnalyticsScreen> {
  Map<String, dynamic>? _data;
  bool _isLoading = true;

  @override
  void initState() {
    super.initState();
    _loadData();
  }

  Future<void> _loadData() async {
    final data = await AnalyticsService().getSummary();
    if (mounted) {
      setState(() {
        _data = data;
        _isLoading = false;
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    if (_isLoading) return const Scaffold(body: Center(child: CircularProgressIndicator()));
    if (_data == null) return const Scaffold(body: Center(child: Text("Gagal memuat data analitik")));

    return Scaffold(
      appBar: AppBar(title: const Text("Analitik Penjualan")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(16),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text("Ringkasan 30 Hari", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            Row(
              children: [
                _buildKPICard("Pesanan", "${_data!['last_30_days']['total_orders']}", Icons.receipt, Colors.blue),
                _buildKPICard("Omzet", "Rp ${_data!['last_30_days']['total_revenue']}", Icons.payments, Colors.green),
              ],
            ),
            const SizedBox(height: 32),
            const Text("Forecasting Stok (BOM)", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            ...(_data!['forecasting'] as List).map((item) => Card(
              child: ListTile(
                leading: const Icon(Icons.inventory_2, color: Colors.orange),
                title: Text(item['material_name']),
                subtitle: Text("Butuh: ${item['estimated_needs']} ${item['unit']} | Sisa: ${item['current_stock']}"),
                trailing: Container(
                  padding: const EdgeInsets.symmetric(horizontal: 8, vertical: 4),
                  decoration: BoxDecoration(
                    color: item['status'] == 'Restock Segera' ? Colors.red : Colors.orange,
                    borderRadius: BorderRadius.circular(4),
                  ),
                  child: Text(
                    item['status'],
                    style: const TextStyle(color: Colors.white, fontSize: 12, fontWeight: FontWeight.bold),
                  ),
                ),
              ),
            )),
            const SizedBox(height: 32),
            const Text("Produk Terlaris", style: TextStyle(fontSize: 18, fontWeight: FontWeight.bold)),
            const SizedBox(height: 16),
            ...(_data!['top_products'] as List).map((p) => ListTile(
              leading: const Icon(Icons.star, color: Colors.amber),
              title: Text(p['name']),
              trailing: Text("${p['orders_count']} Pesanan", style: const TextStyle(fontWeight: FontWeight.bold)),
            )),
          ],
        ),
      ),
    );
  }

  Widget _buildKPICard(String title, String value, IconData icon, Color color) {
    return Expanded(
      child: Card(
        child: Padding(
          padding: const EdgeInsets.all(16.0),
          child: Column(
            crossAxisAlignment: CrossAxisAlignment.start,
            children: [
              Icon(icon, color: color),
              const SizedBox(height: 8),
              Text(title, style: const TextStyle(color: Colors.grey)),
              Text(value, style: const TextStyle(fontSize: 16, fontWeight: FontWeight.bold), overflow: TextOverflow.ellipsis),
            ],
          ),
        ),
      ),
    );
  }
}
