import Image from 'next/image';

export default function Header() {
  return (
    <header className="w-full px-6 py-4 md:px-16 flex justify-between items-center bg-transparent relative z-20">
      {/* Logo de la Municipalidad */}
      <Image
        src="/logo_munimolina.png"
        alt="Municipalidad de La Molina"
        width={220}
        height={80}
        priority
        className="h-14 md:h-18 w-auto brightness-0 invert drop-shadow-md"
      />
    </header>
  );
}