import { render, waitFor } from '@testing-library/react';
import { App } from './App';

test('renders learn react link', async () => {
  const { getByText } = render(<App />);

  await waitFor(() => getByText(/Hello World!/i));
});
