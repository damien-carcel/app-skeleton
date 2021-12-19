import { render, screen, waitFor } from '@testing-library/react';
import { App } from './App';

test('renders learn react link', async () => {
  render(<App />);

  await waitFor(() => screen.findByText('Hello World!'));

  expect(screen.getByText('Hello World!')).toBeVisible();
});
