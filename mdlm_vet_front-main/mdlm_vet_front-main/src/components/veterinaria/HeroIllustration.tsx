import Image from 'next/image';

export default function HeroIllustration() {
  return (
    <div className="relative flex items-center justify-center w-full max-w-lg">
      {/* Glow effect behind the illustration */}
      <div className="absolute inset-0 bg-[#2ecc71]/20 blur-3xl rounded-full scale-75" />
      
      {/* Dog SVG illustration */}
      <div className="relative z-10 drop-shadow-2xl">
        <Image
          src="/dog.svg"
          alt="Ilustración de mascota - Veterinaria Municipal"
          width={420}
          height={420}
          priority
          className="w-[320px] md:w-[420px] h-auto filter brightness-0 invert opacity-90 
                     hover:scale-105 transition-transform duration-500 ease-out"
        />
      </div>
    </div>
  );
}