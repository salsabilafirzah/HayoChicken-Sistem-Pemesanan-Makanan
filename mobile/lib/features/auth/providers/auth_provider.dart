import 'package:flutter_riverpod/flutter_riverpod.dart';
import '../models/user_model.dart';
import '../services/auth_service.dart';

enum AuthStatus { initial, loading, authenticated, unauthenticated }

class AuthState {
  final AuthStatus status;
  final UserModel? user;
  final String? errorMessage;

  AuthState({this.status = AuthStatus.initial, this.user, this.errorMessage});

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

  Future<void> login(String email, String password) async {
    state = state.copyWith(status: AuthStatus.loading);
    final result = await _authService.login(email, password);
    
    if (result['success']) {
      state = state.copyWith(status: AuthStatus.authenticated, user: result['user']);
    } else {
      state = state.copyWith(status: AuthStatus.unauthenticated, errorMessage: result['message']);
    }
  }

  Future<void> logout() async {
    await _authService.logout();
    state = AuthState(status: AuthStatus.unauthenticated);
  }
}

final authProvider = StateNotifierProvider<AuthNotifier, AuthState>((ref) {
  return AuthNotifier();
});
