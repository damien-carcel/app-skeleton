type HelloProps = { personToGreet: string };

const Greetings = ({ personToGreet }: HelloProps) => {
  return <div>Hello {personToGreet}!</div>;
};

export default Greetings;
