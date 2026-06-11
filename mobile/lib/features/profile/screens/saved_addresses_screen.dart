import 'package:flutter/material.dart';
import 'package:google_fonts/google_fonts.dart';
import '../../../../core/theme/app_theme.dart';

class SavedAddressesScreen extends StatelessWidget {
  const SavedAddressesScreen({super.key});

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      backgroundColor: const Color(0xFFF9F4EB),
      body: Stack(
        children: [
          Column(
            children: [
              Container(
                padding: const EdgeInsets.fromLTRB(20, 60, 20, 24),
                decoration: const BoxDecoration(
                  color: AppColors.primary,
                  borderRadius: BorderRadius.only(bottomLeft: Radius.circular(35), bottomRight: Radius.circular(35)),
                ),
                child: Row(
                  children: [
                    GestureDetector(
                      onTap: () => Navigator.pop(context),
                      child: Container(
                        padding: const EdgeInsets.all(8),
                        decoration: BoxDecoration(color: Colors.white.withOpacity(0.2), shape: BoxShape.circle),
                        child: const Icon(Icons.chevron_left, color: Colors.white),
                      ),
                    ),
                    const SizedBox(width: 16),
                    Text("Alamat Tersimpan", style: GoogleFonts.inter(color: Colors.white, fontSize: 20, fontWeight: FontWeight.w900)),
                  ],
                ),
              ),
              Expanded(
                child: ListView(
                  padding: const EdgeInsets.all(20),
                  children: [
                    _buildAddressCard("RUMAH", "Zainab Feizia", "Jl. Kampus No. 12, Purwokerto, Banyumas, Jawa Tengah 53122", true, Icons.home_outlined),
                    _buildAddressCard("KANTOR", "UNSOED", "Jl. Prof. Dr. HR. Boenyamin No. 708, Grendeng, Purwokerto Utara 53122", false, Icons.work_outline),
                    const SizedBox(height: 100),
                  ],
                ),
              ),
            ],
          ),
          Positioned(
            bottom: 40, left: 24, right: 24,
            child: SizedBox(
              width: double.infinity, height: 60,
              child: ElevatedButton(
                onPressed: () {},
                style: ElevatedButton.styleFrom(
                  backgroundColor: AppColors.primary,
                  shape: RoundedRectangleBorder(borderRadius: BorderRadius.circular(50)),
                ),
                child: Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    const Icon(Icons.add, color: Colors.white),
                    const SizedBox(width: 8),
                    Text("Tambah Alamat Baru", style: GoogleFonts.inter(fontWeight: FontWeight.w900, fontSize: 15, color: Colors.white)),
                  ],
                ),
              ),
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildAddressCard(String label, String name, String address, bool isMain, IconData icon) {
    return Container(
      margin: const EdgeInsets.only(bottom: 16),
      padding: const EdgeInsets.all(20),
      decoration: BoxDecoration(color: Colors.white, borderRadius: BorderRadius.circular(24)),
      child: Column(
        crossAxisAlignment: CrossAxisAlignment.start,
        children: [
          Row(
            children: [
              Container(padding: const EdgeInsets.all(8), decoration: BoxDecoration(color: const Color(0xFFF5EFE6), borderRadius: BorderRadius.circular(10)), child: Icon(icon, color: const Color(0xFF8B7A6A))),
              const SizedBox(width: 12),
              Column(
                crossAxisAlignment: CrossAxisAlignment.start,
                children: [
                  Text(label, style: GoogleFonts.inter(color: AppColors.primary, fontWeight: FontWeight.w900, fontSize: 12)),
                  Text(name, style: GoogleFonts.inter(fontWeight: FontWeight.w800, fontSize: 15)),
                ],
              ),
              const Spacer(),
              if (isMain) 
                Container(
                  padding: const EdgeInsets.symmetric(horizontal: 10, vertical: 4),
                  decoration: BoxDecoration(color: AppColors.primary, borderRadius: BorderRadius.circular(8)),
                  child: Text("Utama", style: GoogleFonts.inter(color: Colors.white, fontSize: 10, fontWeight: FontWeight.w800)),
                ),
            ],
          ),
          const SizedBox(height: 12),
          Text(address, style: GoogleFonts.inter(color: Colors.grey, fontSize: 13, height: 1.5)),
          const SizedBox(height: 16),
          Row(
            children: [
              _btn("Edit", const Color(0xFFF5EFE6), const Color(0xFF8B7A6A)),
              const SizedBox(width: 12),
              _btn("Hapus", const Color(0xFFFFFBFA), Colors.redAccent),
            ],
          ),
        ],
      ),
    );
  }

  Widget _btn(String t, Color b, Color f) {
    return Container(
      padding: const EdgeInsets.symmetric(horizontal: 20, vertical: 8),
      decoration: BoxDecoration(color: b, borderRadius: BorderRadius.circular(12)),
      child: Text(t, style: GoogleFonts.inter(color: f, fontSize: 12, fontWeight: FontWeight.w800)),
    );
  }
}
