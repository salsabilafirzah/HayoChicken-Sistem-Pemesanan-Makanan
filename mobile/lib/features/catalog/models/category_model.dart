class CategoryModel {
  final int id;
  final String name;
  final int sortOrder;

  CategoryModel({
    required this.id,
    required this.name,
    this.sortOrder = 0,
  });

  factory CategoryModel.fromJson(Map<String, dynamic> json) {
    return CategoryModel(
      id: json['id'] ?? 0,
      name: json['name'] ?? '',
      sortOrder: json['sort_order'] ?? 0,
    );
  }
}
