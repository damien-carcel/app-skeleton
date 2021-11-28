import { useEffect, useState } from 'react';
import { createHeaders, url } from './api';

export const useGreetPerson = () => {
  const [personToGreet, setPersonToGreet] = useState<string>('');
  const [error, setError] = useState<Error | null>(null);
  const [loading, setLoading] = useState<boolean>(false);

  useEffect(() => {
    const greetPerson = async () => {
      try {
        setLoading(true);
        const response = await fetch(url('/api/personToGreet'), {
          headers: createHeaders(),
          method: 'GET',
          mode: 'cors',
        });

        if (response.status >= 400 && response.status < 600) {
          setError(new Error(`Bad response from the server (status ${response.status})!`));
        }

        const personToGreet = await response.json();

        setLoading(false);

        setPersonToGreet(personToGreet);
      } catch (error) {
        if (error instanceof Error) {
          setError(error);
        } else {
          setError(new Error('Something went terribly wrong…'));
        }
      }
    };

    greetPerson();
  }, []);

  return { personToGreet, error, loading };
};
