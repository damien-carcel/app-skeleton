import React from 'react';
import { render, screen } from '@testing-library/react';
import { UserData } from '../../../src/api/user';
import Users from '../../../src/components/Users';

describe('Users component', () => {
  it('render a list of users', () => {
    const users: UserData[] = [
      {
        id: '8a8b6376-125c-4933-9453-606065ed00f0',
        email: 'fake.user1@whatever.com',
        firstName: 'Fake User',
        lastName: 'Number One',
      },
      {
        id: '9a4fafb4-c713-4434-bc43-06bf5e0a42d2',
        email: 'fake.user2@whatever.com',
        firstName: 'Fake User',
        lastName: 'Number Two',
      },
    ];

    render(<Users users={users} />);

    expect(screen.getByText('fake.user1@whatever.com - Fake User Number One')).toBeVisible();
    expect(screen.getByText('fake.user2@whatever.com - Fake User Number Two')).toBeVisible();
  });
});
