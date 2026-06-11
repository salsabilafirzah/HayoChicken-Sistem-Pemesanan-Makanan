import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/user_model.dart';
import '../services/auth_service.dart';

enum AuthStatus { initial, loading, authenticated, unauthenticated }

class AuthState {
  final AuthStatus status;
  final UserModel? user;
  final String? errorMessage;

  AuthState({this.status = AuthStatus.initial, this.user, this.errorMessage});

  bool get isLoading => status == AuthStatus.loading;

  AuthState copyWith({AuthStatus? status, UserModel? user, String? errorMessage}) {
    return AuthState(
      status: status ?? this.status,
      user: user ?? this.user,
      errorMessage: errorMessage,
    );
  }
}

class AuthNotifier extends StateNotifier<AuthState> {
  final AuthService _authService = AuthService();

  AuthNotifier() : super(AuthState());

  Future<bool> login(String email, String password) async {
    state = state.copyWith(status: AuthStatus.loading);
    final result = await _authService.login(email, password);
    
    if (result['success']) {
      state = state.copyWith(status: AuthStatus.authenticated, user: result['user']);
      return true;
    } else {
      state = state.copyWith(status: AuthStatus.unauthenticated, errorMessage: result['message']);
      return false;
    }
  }

  Future<void> logout() async {
    await _authService.logout();
    state = AuthState(status: AuthStatus.unauthenticated);
  }

  Future<bool> updateProfile({required String name, required String email, required String phone}) async {
    state = state.copyWith(status: AuthStatus.loading);
    
    final result = await _authService.updateProfile(name: name, phone: phone);
    
    if (result['success']) {
      final currentUser = state.user;
      final updatedUser = UserModel(
        id: currentUser?.id ?? 0,
        name: name,
        email: email, // Email stays same in current logic
        phone: phone,
        role: currentUser?.role ?? 'CUSTOMER',
        profileImageUrl: currentUser?.profileImageUrl,
      );
      
      state = state.copyWith(user: updatedUser, status: AuthStatus.authenticated);
      return true;
    } else {
      state = state.copyWith(status: AuthStatus.authenticated, errorMessage: result['message']);
      return false;
    }
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});
