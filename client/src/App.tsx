import { GreetingsPage } from './greetings/GreetingsPage';
import styled from 'styled-components';

const AppContainer = styled.div`
  font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, 'Fira Sans',
    'Droid Sans', 'Helvetica Neue', sans-serif;
  -webkit-font-smoothing: antialiased;
  -moz-osx-font-smoothing: grayscale;
  margin: 0;
  text-align: center;
`;

export const App = () => {
  return (
    <AppContainer>
      <GreetingsPage />
    </AppContainer>
  );
};
