export default {
  all: jest.fn()
    .mockImplementation(() => Promise.resolve({
      data: [
        { 'slug': 'addresses:delete', 'is_default': false, 'name': 'Delete addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'addresses:read', 'is_default': false, 'name': 'Read addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'addresses:write', 'is_default': false, 'name': 'Add/Edit addresses', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'countries:delete', 'is_default': false, 'name': 'Delete countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'countries:read', 'is_default': true, 'name': 'Read countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'countries:write', 'is_default': false, 'name': 'Add/Edit countries', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'permissions:read', 'is_default': false, 'name': 'Read permissions', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'personal', 'is_default': true, 'name': 'Access to personal information', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'users:delete', 'is_default': true, 'name': 'Delete users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'users:read', 'is_default': true, 'name': 'Read users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' },
        { 'slug': 'users:write', 'is_default': true, 'name': 'Add/Edit users', 'created_at': '2020-03-15T20:00:00.000000Z', 'updated_at': '2020-03-15T20:00:00.000000Z' }
      ]
    }))
};
