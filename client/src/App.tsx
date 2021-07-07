import { useState } from 'react';

import Hello from './hello/Hello';
import { getPersonToGreet } from './hello/api';

import './App.css';

const App = () => {
  const [personToGreet, setPersonToGreet] = useState<string>('');
  const [errorMessage, setErrorMessage] = useState<string>('');

  getPersonToGreet()
    .then((response: Response) => {
      return {
        data: response.json(),
        responseStatus: response.status,
      };
    })
    .then((result: { data: Promise<string>; responseStatus: number }) => {
      result.data.then((personToGreet) => setPersonToGreet(personToGreet));
    })
    .catch((error) => setErrorMessage(error.message));

  return (
    <div className="App">
      {errorMessage ? (
        <p>Encountered error: &quot;{errorMessage}&quot;</p>
      ) : (
        <div>
          <Hello personToGreet={personToGreet} />
        </div>
      )}
    </div>
  );
};

export default App;
