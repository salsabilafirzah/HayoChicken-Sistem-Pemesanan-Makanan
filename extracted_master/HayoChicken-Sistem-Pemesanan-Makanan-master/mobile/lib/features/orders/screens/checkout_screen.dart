import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import '../services/order_service.dart';
import '../../cart/providers/cart_provider.dart';

class CheckoutScreen extends ConsumerStatefulWidget {
  const CheckoutScreen({super.key});

  @override
  ConsumerState<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends ConsumerState<CheckoutScreen> {
  final _addressController = TextEditingController();
  String _paymentMethod = 'COD';
  bool _isLoading = false;

  void _handleCheckout() async {
    if (_addressController.text.isEmpty) {
      ScaffoldMessenger.of(context).showSnackBar(const SnackBar(content: Text("Alamat wajib diisi")));
      return;
    }

    setState(() => _isLoading = true);
    final result = await OrderService().createOrder(
      address: _addressController.text,
      paymentMethod: _paymentMethod,
    );
    setState(() => _isLoading = false);

    if (result['success']) {
      ref.read(cartProvider.notifier).refreshCart();
      if (mounted) {
        showDialog(
          context: context,
          barrierDismissible: false,
          builder: (context) => AlertDialog(
            title: const Text("Berhasil!"),
            content: Text("Pesanan ${result['data']['order_number']} telah dibuat."),
            actions: [
              TextButton(onPressed: () => context.go('/home'), child: const Text("OK")),
            ],
          ),
        );
      }
    } else {
      if (mounted) ScaffoldMessenger.of(context).showSnackBar(SnackBar(content: Text(result['message'])));
    }
  }

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(cartProvider);

    return Scaffold(
      appBar: AppBar(title: const Text("Konfirmasi Pesanan")),
      body: SingleChildScrollView(
        padding: const EdgeInsets.all(24),
        child: Column(
          crossAxisAlignment: CrossAxisAlignment.start,
          children: [
            const Text("Alamat Pengiriman", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
            const SizedBox(height: 8),
            TextField(
              controller: _addressController,
              maxLines: 2,
              decoration: const InputDecoration(
                hintText: "Contoh: Jl. Merdeka No. 45, Purwokerto",
                border: OutlineInputBorder(),
              ),
            ),
            const SizedBox(height: 24),
            const Text("Metode Pembayaran", style: TextStyle(fontWeight: FontWeight.bold, fontSize: 18)),
            RadioListTile(
              title: const Text("Bayar di Tempat (COD)"),
              value: 'COD',
              groupValue: _paymentMethod,
              onChanged: (v) => setState(() => _paymentMethod = v!),
            ),
            RadioListTile(
              title: const Text("Tunai di Kasir (CASH)"),
              value: 'CASH',
              groupValue: _paymentMethod,
              onChanged: (v) => setState(() => _paymentMethod = v!),
            ),
            RadioListTile(
              title: const Text("Transfer QRIS"),
              subtitle: const Text("Bayar dulu, upload bukti kemudian"),
              value: 'QRIS_MANUAL',
              groupValue: _paymentMethod,
              onChanged: (v) => setState(() => _paymentMethod = v!),
            ),
            if (_paymentMethod == 'QRIS_MANUAL') 
              Padding(
                padding: const EdgeInsets.all(16.0),
                child: Container(
                  padding: const EdgeInsets.all(16),
                  decoration: BoxDecoration(color: Colors.grey[100], borderRadius: BorderRadius.circular(8)),
                  child: const Column(
                    children: [
                      Icon(Icons.qr_code_2, size: 150),
                      Text("Scan QR di atas lalu transfer sesuai total belanja"),
                    ],
                  ),
                ),
              ),
            const Divider(height: 48),
            Row(
              mainAxisAlignment: MainAxisAlignment.spaceBetween,
              children: [
                const Text("Total Pembayaran", style: TextStyle(fontSize: 16)),
                Text("Rp ${cartState.totalAmount}", style: const TextStyle(fontSize: 24, fontWeight: FontWeight.bold, color: Colors.red)),
              ],
            ),
            const SizedBox(height: 32),
            SizedBox(
              width: double.infinity,
              child: ElevatedButton(
                onPressed: _isLoading ? null : _handleCheckout,
                style: ElevatedButton.styleFrom(backgroundColor: Colors.red, padding: const EdgeInsets.symmetric(vertical: 16)),
                child: _isLoading 
                  ? const CircularProgressIndicator(color: Colors.white)
                  : const Text("BUAT PESANAN SEKARANG", style: TextStyle(color: Colors.white, fontWeight: FontWeight.bold)),
              ),
            ),
          ],
        ),
      ),
    );
  }
}
