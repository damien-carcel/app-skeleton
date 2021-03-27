import React from 'react';
import { render, screen } from '@testing-library/react';
import { UserData } from '../../../src/api/user';
import User from '../../../src/components/User';

describe('Users component', () => {
  it('render a list of users', () => {
    const user: UserData = {
      id: '06554bcb-3318-4470-ad63-1a9c3d6fa6ef',
      email: 'fake.user@whatever.com',
      firstName: 'Fake',
      lastName: 'User',
    };

    render(<User user={user} />);

    expect(screen.getByText('fake.user@whatever.com - Fake User')).toBeVisible();
  });
});
