type HelloProps = { personToGreet: string };

const Hello = ({ personToGreet }: HelloProps) => {
  return <p>Hello {personToGreet}!</p>;
};

export default Hello;
