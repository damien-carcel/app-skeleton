import { useGreetPerson } from './useGreetPerson';
import Greetings from './Greetings';

export const GreetingsPage = () => {
  const { personToGreet, error, loading } = useGreetPerson();

  if (loading) {
    return <div>Loading…</div>;
  }

  if (error) {
    return <div>Loading…</div>;
  }

  return <Greetings personToGreet={personToGreet} />;
};
