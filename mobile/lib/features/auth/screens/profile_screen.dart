import 'package:flutter/material.dart';
import 'package:flutter_riverpod/flutter_riverpod.dart';
import 'package:go_router/go_router.dart';
import 'package:google_fonts/google_fonts.dart';
import '../providers/auth_provider.dart';
import '../../../core/theme/app_theme.dart';

class ProfileScreen extends ConsumerWidget {
  const ProfileScreen({super.key});

  @override
  Widget build(BuildContext context, WidgetRef ref) {
    final authState = ref.watch(authProvider);
    final user = authState.user;

    return Scaffold(
      backgroundColor: const Color(0xFFF8EFDE),
      body: Column(
        children: [
          Container(
            width: double.infinity,
            padding: const EdgeInsets.fromLTRB(24, 70, 24, 30),
            decoration: const BoxDecoration(
              color: AppColors.primary,
              borderRadius: BorderRadius.only(bottomLeft: Radius.circular(45), bottomRight: Radius.circular(45)),
            ),
            child: Column(
              children: [
                Container(
                  width: 110, height: 110,
                  decoration: const BoxDecoration(color: Color(0xFFF1B434), shape: BoxShape.circle),
                  child: const Icon(Icons.person, size: 70, color: Color(0xFF9B1A1A)),
                ),
                const SizedBox(height: 16),
                Row(
                  mainAxisAlignment: MainAxisAlignment.center,
                  children: [
                    Text(user?.name ?? "Memuat...", style: GoogleFonts.inter(color: Colors.white, fontSize: 24, fontWeight: FontWeight.w900)),
                    const SizedBox(width: 8),
                    GestureDetector(
                      onTap: () => context.push('/profile/edit'),
                      child: Icon(Icons.edit_square, color: Colors.white.withOpacity(0.8), size: 20),
                    ),
                  ],
                ),
                Text(user?.email ?? "", style: GoogleFonts.inter(color: Colors.white70, fontSize: 13, fontWeight: FontWeight.w500)),
              ],
            ),
          ),

          const SizedBox(height: 32),
          Expanded(
            child: ListView(
              padding: const EdgeInsets.symmetric(horizontal: 20),
              children: [
                _buildMenuCard(context, Icons.description_outlined, "Riwayat Pesanan", "/profile/history"),
                const SizedBox(height: 16),
                _buildMenuCard(context, Icons.local_shipping_outlined, "Pesanan Aktif", "/profile/orders"),
                const SizedBox(height: 16),
                _buildMenuCard(context, Icons.notifications_none, "Notifikasi", "/profile/notif"),
                const SizedBox(height: 16),
                _buildMenuCard(context, Icons.lock_outline, "Ubah Password", "/profile/password"),
                const SizedBox(height: 16),
                
                // REDESIGNED LOGOUT BUTTON (Match Image)
                _buildMenuCard(
                  context, 
                  Icons.logout_rounded, 
                  "Keluar", 
                  "/login", 
                  isLogout: true,
                  onLogout: () => ref.read(authProvider.notifier).logout(),
                ),
                const SizedBox(height: 160), // Extra space for sticky order bar
              ],
            ),
          ),
        ],
      ),
    );
  }

  Widget _buildMenuCard(BuildContext context, IconData icon, String title, String route, {bool isLogout = false, VoidCallback? onLogout}) {
    Color mainColor = isLogout ? AppColors.primary : const Color(0xFF1A1A1A);
    Color iconColor = isLogout ? AppColors.primary : const Color(0xFF8B7A6A);

    return GestureDetector(
      onTap: () {
        if (isLogout) {
          if (onLogout != null) onLogout();
          context.go(route);
        } else {
          context.push(route);
        }
      },
      child: Container(
        margin: const EdgeInsets.only(bottom: 0), // Margin handled by SizedBox in ListView
        padding: const EdgeInsets.all(18),
        decoration: BoxDecoration(
          color: Colors.white,
          borderRadius: BorderRadius.circular(20),
          boxShadow: [BoxShadow(color: Colors.black.withOpacity(0.02), blurRadius: 10, offset: const Offset(0, 4))],
        ),
        child: Row(
          children: [
            Container(
              padding: const EdgeInsets.all(10),
              decoration: BoxDecoration(color: isLogout ? AppColors.primary.withOpacity(0.05) : const Color(0xFFF5E6D3).withOpacity(0.5), borderRadius: BorderRadius.circular(12)),
              child: Icon(icon, color: iconColor, size: 22),
            ),
            const SizedBox(width: 16),
            Text(title, style: GoogleFonts.inter(fontSize: 15, fontWeight: FontWeight.w800, color: mainColor)),
            const Spacer(),
            Icon(Icons.chevron_right, color: isLogout ? AppColors.primary.withOpacity(0.6) : const Color(0xFFBBAA99), size: 20),
          ],
        ),
      ),
    );
  }
}
