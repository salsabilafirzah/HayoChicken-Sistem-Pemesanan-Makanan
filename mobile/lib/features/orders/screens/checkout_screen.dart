import 'dart:ui';
import 'dart:io';
import 'dart:typed_data';
import 'package:dio/dio.dart';
import 'package:flutter/services.dart';
import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import 'package:gal/gal.dart';
import 'package:image_picker/image_picker.dart';
import 'package:path_provider/path_provider.dart';
import '../services/order_service.dart';
import '../../cart/providers/cart_provider.dart';
import '../../../core/theme/app_theme.dart';

class CheckoutScreen extends ConsumerStatefulWidget {
  const CheckoutScreen({super.key});

  @override
  ConsumerState<CheckoutScreen> createState() => _CheckoutScreenState();
}

class _CheckoutScreenState extends ConsumerState<CheckoutScreen> {
  String _selectedMethod = "COD";
  final TextEditingController _addressController = TextEditingController();
  final TextEditingController _patokanController = TextEditingController();
  String? _addressError;
  String? _proofError;
  XFile? _proofImage;
  bool _isProofUploaded = false;

  @override
  void dispose() {
    _addressController.dispose();
    _patokanController.dispose();
    super.dispose();
  }

  @override
  Widget build(BuildContext context) {
    final cartState = ref.watch(cartProvider);

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Stack(
        children: [
          Column(
            children: [
              // HEADER
              Container(
                padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
                ),
                child: Row(
                  children: [
                    _buildCircleNav(Icons.chevron_left, () => context.pop()),
                    const SizedBox(width: 16),
                    Text("Checkout", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),

              Expanded(
                child: ListView(
                  padding: const EdgeInsets.fromLTRB(24, 24, 24, 140),
                  children: [
                    _buildCard(
                      title: "Alamat Pengiriman",
                      child: Column(
                        crossAxisAlignment: CrossAxisAlignment.start,
                        children: [
                          _buildInput("Alamat lengkap kamu...", controller: _addressController, hasError: _addressError != null),
                          if (_addressError != null)
                            Padding(
                              padding: const EdgeInsets.only(top: 8, left: 4),
                              child: Text(_addressError!, style: GoogleFonts.inter(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                            ),
                          const SizedBox(height: 12),
                          _buildInput("Patokan (Gedung, Lantai, dll)", controller: _patokanController),
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    _buildCard(
                      title: "Metode Pembayaran",
                      child: Column(
                        children: [
                          _buildPaymentOption(
                            icon: Icons.delivery_dining_outlined,
                            title: "Cash on Delivery (COD)",
                            subtitle: "Bayar tunai saat pesanan tiba",
                            value: "COD",
                          ),
                          const Padding(padding: EdgeInsets.symmetric(horizontal: 0), child: Divider(height: 1, color: Color(0xFFF5EFE6))),
                          _buildPaymentOption(
                            icon: Icons.qr_code_scanner_rounded,
                            title: "QRIS (Transfer Manual)",
                            subtitle: "Scan & unggah bukti transfer",
                            value: "QRIS_MANUAL",
                          ),
                          
                          if (_selectedMethod == "QRIS_MANUAL") ...[
                            const SizedBox(height: 16),
                            Container(
                              padding: const EdgeInsets.all(12),
                              decoration: BoxDecoration(
                                color: const Color(0xFFFFF9EE),
                                borderRadius: BorderRadius.circular(10),
                                border: Border.all(color: const Color(0xFFFFE5B4)),
                              ),
                              child: Row(
                                crossAxisAlignment: CrossAxisAlignment.start,
                                children: [
                                  const Icon(Icons.warning_amber_rounded, size: 18, color: Color(0xFF8B5E3C)),
                                  const SizedBox(width: 8),
                                  Expanded(
                                    child: Text(
                                      "Scan QRIS di bawah, transfer sesuai nominal, lalu unggah bukti transfer. Pesanan akan diverifikasi manual oleh penjual.",
                                      style: GoogleFonts.inter(fontSize: 11, color: const Color(0xFF8B5E3C), fontWeight: FontWeight.w600, height: 1.4),
                                    ),
                                  ),
                                ],
                              ),
                            ),
                            const SizedBox(height: 20),
                            Center(
                              child: Container(
                                padding: const EdgeInsets.all(16),
                                decoration: BoxDecoration(
                                  color: Colors.white,
                                  borderRadius: BorderRadius.circular(24),
                                  boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.04), blurRadius: 20, offset: const Offset(0, 10))],
                                ),
                                child: ClipRRect(
                                  borderRadius: BorderRadius.circular(12),
                                  child: Image.asset(
                                    'assets/images/qris_toko.jpg',
                                    width: 250, height: 320, 
                                    fit: BoxFit.contain,
                                    errorBuilder: (context, error, stackTrace) => Container(
                                      width: 250, height: 320, color: Colors.grey[100],
                                      child: const Icon(Icons.qr_code_2, size: 100, color: AppColors.primary)
                                    ),
                                  ),
                                ),
                              ),
                            ),
                            const SizedBox(height: 16),
                            // REAL SAVE BUTTON
                            Center(
                              child: OutlinedButton.icon(
                                onPressed: () async {
                                  showDialog(
                                    context: context,
                                    barrierDismissible: false,
                                    builder: (context) => const Center(child: CircularProgressIndicator(color: AppColors.primary)),
                                  );
                                  
                                  try {
                                    final byteData = await rootBundle.load('assets/images/qris_toko.jpg');
                                    
                                    // Save to temp file because GAL needs a file path
                                    final tempDir = await getTemporaryDirectory();
                                    final tempPath = "${tempDir.path}/qris_hayochicken_${DateTime.now().millisecondsSinceEpoch}.jpg";
                                    final file = File(tempPath);
                                    await file.writeAsBytes(byteData.buffer.asUint8List(byteData.offsetInBytes, byteData.lengthInBytes));

                                    await Gal.putImage(tempPath);
                                    
                                    if (mounted) {
                                      Navigator.pop(context);
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        const SnackBar(
                                          content: Text("QRIS berhasil disimpan ke galeri"),
                                          backgroundColor: Colors.green,
                                          behavior: SnackBarBehavior.floating,
                                        )
                                      );
                                    }
                                  } catch (e) {
                                    if (mounted) {
                                      Navigator.pop(context);
                                      ScaffoldMessenger.of(context).showSnackBar(
                                        SnackBar(content: Text("Gagal simpan nih Bos: $e"), backgroundColor: Colors.red)
                                      );
                                    }
                                  }
                                },
                                icon: const Icon(Icons.file_download_outlined, size: 18),
                                label: Text("Simpan QRIS ke Galeri", style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 13)),
                                style: OutlinedButton.styleFrom(
                                  foregroundColor: const Color(0xFF8B1A1A),
                                  backgroundColor: const Color(0xFFF5EFE6),
                                  side: const BorderSide(color: Color(0xFF8B1A1A)),
                                  padding: const EdgeInsets.symmetric(horizontal: 24, vertical: 12),
                                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                                ),
                              ),
                            ),
                            const SizedBox(height: 16),
                            Center(
                              child: Text(
                                "Total: Rp${cartState.totalAmount.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}",
                                style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w900, color: const Color(0xFF8B1A1A)),
                              ),
                            ),
                            const SizedBox(height: 16),
                            _buildDashedUploadBox(),
                            if (_proofError != null)
                              Padding(
                                padding: const EdgeInsets.only(top: 8),
                                child: Center(
                                  child: Text(_proofError!, style: GoogleFonts.inter(color: AppColors.primary, fontSize: 12, fontWeight: FontWeight.w700)),
                                ),
                              ),
                          ],
                        ],
                      ),
                    ),
                    const SizedBox(height: 20),

                    _buildCard(
                      title: "",
                      child: Column(
                        children: [
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text("Subtotal (${cartState.totalCount} item)", style: GoogleFonts.inter(color: Colors.grey, fontSize: 14, fontWeight: FontWeight.w600)),
                              Text("Rp${cartState.totalAmount.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}", style: GoogleFonts.inter(color: Colors.black, fontSize: 14, fontWeight: FontWeight.w700)),
                            ],
                          ),
                          const SizedBox(height: 16),
                          _buildDashedDivider(),
                          const SizedBox(height: 16),
                          Row(
                            mainAxisAlignment: MainAxisAlignment.spaceBetween,
                            children: [
                              Text("Total", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 16)),
                              Text("Rp${cartState.totalAmount.toString().replaceAllMapped(RegExp(r'(\d{1,3})(?=(\d{3})+(?!\d))'), (Match m) => '${m[1]}.')}", style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 20)),
                            ],
                          ),
                        ],
                      ),
                      noTitle: true,
                    ),
                  ],
                ),
              ),
            ],
          ),

          Positioned(
            bottom: 30, left: 24, right: 24,
            child: Container(
              decoration: BoxDecoration(
                boxShadow: [BoxShadow(color: AppColors.primary.withOpacity(0.3), blurRadius: 20, offset: const Offset(0, 10))],
              ),
              child: SizedBox(
                width: double.infinity, height: 60,
                child: ElevatedButton(
                  onPressed: () => _handlePlaceOrder(),
                  style: ElevatedButton.styleFrom(
                    backgroundColor: AppColors.primary,
                    shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                    elevation: 0,
                  ),
                  child: Text("Konfirmasi Pesanan", style: GoogleFonts.inter(fontSize: 16, fontWeight: FontWeight.w900, color: Colors.white)),
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildDashedUploadBox() {
    return InkWell(
      onTap: () async {
        final ImagePicker picker = ImagePicker();
        final XFile? image = await picker.pickImage(source: ImageSource.gallery);
        
        if (image != null) {
          final file = File(image.path);
          final sizeInBytes = await file.length();
          final sizeInMb = sizeInBytes / (1024 * 1024);
          
          if (sizeInMb > 2) {
             if (mounted) {
               ScaffoldMessenger.of(context).showSnackBar(
                 const SnackBar(content: Text("File terlalu besar, maksimal 2 MB bos!"), backgroundColor: Colors.red)
               );
             }
             return;
          }
          
          setState(() {
            _proofImage = image;
            _isProofUploaded = true;
            _proofError = null;
          });
        }
      },
      child: Container(
        width: double.infinity,
        padding: const EdgeInsets.symmetric(vertical: 20),
        decoration: BoxDecoration(
          color: const Color(0xFFF8EFDE).withOpacity(0.5),
          borderRadius: BorderRadius.circular(16),
        ),
        child: CustomPaint(
          painter: _DashedRectPainter(color: const Color(0xFFBBAA99)),
          child: Padding(
            padding: const EdgeInsets.symmetric(horizontal: 16),
            child: Row(
              mainAxisAlignment: MainAxisAlignment.center,
              children: [
                const Icon(Icons.attachment_rounded, color: Color(0xFF8B1A1A), size: 20),
                const SizedBox(width: 10),
                Flexible(
                  child: Text(
                    _isProofUploaded ? "Terlampir: ${_proofImage!.name}" : "Unggah Bukti Transfer (Maks 2MB)", 
                    style: GoogleFonts.inter(color: const Color(0xFF8B1A1A), fontWeight: FontWeight.w800, fontSize: 12),
                    maxLines: 2,
                    textAlign: TextAlign.center,
                  ),
                ),
              ],
            ),
          ),
        ),
      ),
    );
  }

  Widget _buildDashedDivider() {
    return Row(
      children: List.generate(150 ~/ 2, (index) => Expanded(
        child: Container(
          color: index % 2 == 0 ? Colors.transparent : Colors.grey.withOpacity(0.2),
          height: 1,
        ),
      )),
    );
  }

  Widget _buildCard({required String title, required Widget child, bool noTitle = false}) {
    return Container(
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24), boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.01), blurRadius: 10, offset: const Offset(0, 4))]),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          if (!noTitle) ...[
            Text(title, style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w900, color: const Color(0xFF1A1A1A))),
            const SizedBox(height: 18),
          ],
          child,
        ],
      ),
    );
  }

  Widget _buildInput(String hint, {required TextEditingController controller, bool hasError = false}) {
    return Container(
      clipBehavior: Clip.antiAlias,
      decoration: BoxDecoration(
        color: const Color(0xFFF9F9F9),
        borderRadius: BorderRadius.circular(50),
        border: hasError ? Border.all(color: AppColors.primary, width: 2.0) : Border.all(color: const Color(0xFFF0F0F0), width: 1.5),
      ),
      child: TextField(
        controller: controller,
        style: GoogleFonts.inter(fontSize: 14, fontWeight: FontWeight.w600, color: const Color(0xFF4D4D4D)),
        onChanged: (_) {
          if (_addressError != null) setState(() => _addressError = null);
        },
        decoration: InputDecoration(
          filled: false,
          fillColor: Colors.transparent,
          hintText: hint,
          hintStyle: GoogleFonts.inter(color: const Color(0xFFBBAA99).withOpacity(0.6), fontSize: 14, fontWeight: FontWeight.w500),
          contentPadding: const EdgeInsets.symmetric(horizontal: 20, vertical: 18),
          border: InputBorder.none,
          focusedBorder: InputBorder.none,
          enabledBorder: InputBorder.none,
        ),
      ),
    );
  }

  Widget _buildPaymentOption({required IconData icon, required String title, required String subtitle, required String value}) {
    bool isSelected = _selectedMethod == value;
    return InkWell(
      onTap: () => setState(() => _selectedMethod = value),
      child: Padding(
        padding: const EdgeInsets.symmetric(vertical: 16),
        child: Row(
          children: [
             Icon(icon, color: const Color(0xFF1A1A1A), size: 24),
             const SizedBox(width: 14),
             Expanded(
               child: Column(
                 crossAxisAlignment: CrossAxisAlignment.start,
                 children: [
                   Text(title, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 13, color: const Color(0xFF1A1A1A))),
                   Text(subtitle, style: GoogleFonts.inter(color: Colors.grey, fontSize: 11, fontWeight: FontWeight.w500)),
                 ],
               ),
             ),
             Container(
               width: 22, height: 22,
               decoration: BoxDecoration(
                 shape: BoxShape.circle,
                 border: Border.all(color: isSelected ? AppColors.primary : const Color(0xFFEBE0D0), width: isSelected ? 6 : 2),
               ),
             ),
          ],
        ),
      ),
    );
  }

  Widget _buildCircleNav(IconData icon, VoidCallback onTap) => GestureDetector(onTap: onTap, child: Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle), child: Icon(icon, color: Colors.white, size: 24)));

  void _handlePlaceOrder() async {
    setState(() {
      _addressError = _addressController.text.trim().isEmpty ? "Alamat wajib diisi" : null;
      _proofError = (_selectedMethod == "QRIS_MANUAL" && !_isProofUploaded) ? "Bukti transfer wajib diunggah untuk pembayaran QRIS" : null;
    });

    if (_addressError != null || _proofError != null) return;

    // Show Loading
    showDialog(
      context: context,
      barrierDismissible: false,
      builder: (context) => const Center(child: CircularProgressIndicator(color: AppColors.primary)),
    );

    try {
      final orderService = OrderService();
      MultipartFile? receipt;
      
      if (_selectedMethod == "QRIS_MANUAL" && _proofImage != null) {
        receipt = await MultipartFile.fromFile(
          _proofImage!.path,
          filename: _proofImage!.path.split('/').last,
        );
      }
      final result = await orderService.createOrder(
        address: "${_addressController.text} (${_patokanController.text})",
        paymentMethod: _selectedMethod,
        receiptFile: receipt,
      );

      if (!mounted) return;
      Navigator.pop(context); // Close loading

      if (result['success'] == true) {
        final rootData = result['data']; // This is the full JSON response body
        final orderObj = rootData?['data']; // This is the $order Eloquent model
        final orderNumber = rootData?['order_number'] ?? "#HC-${DateTime.now().millisecondsSinceEpoch}";
        
        context.go('/order-success', extra: {
          'order_number': orderNumber,
          'total_amount': orderObj?['total_amount']?.toString() ?? "0",
        });
      } else {
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text(result['message'] ?? "Gagal membuat pesanan"), backgroundColor: Colors.red)
        );
      }
    } catch (e) {
      if (mounted) {
        Navigator.pop(context);
        ScaffoldMessenger.of(context).showSnackBar(
          SnackBar(content: Text("Error: $e"), backgroundColor: Colors.red)
        );
      }
    }
  }
}

class _DashedRectPainter extends CustomPainter {
  final Color color;
  _DashedRectPainter({required this.color});

  @override
  void paint(Canvas canvas, Size size) {
    var paint = Paint()
      ..color = color
      ..strokeWidth = 1.5
      ..style = PaintingStyle.stroke;
    
    var path = Path()
      ..addRRect(RRect.fromRectAndRadius(Rect.fromLTWH(0, 0, size.width, size.height), const Radius.circular(16)));

    double dashWidth = 8, dashSpace = 5, distance = 0;
    for (PathMetric pathMetric in path.computeMetrics()) {
      while (distance < pathMetric.length) {
        canvas.drawPath(pathMetric.extractPath(distance, distance + dashWidth), paint);
        distance += dashWidth + dashSpace;
      }
    }
  }

  @override
  bool shouldRepaint(CustomPainter oldDelegate) => false;
}
