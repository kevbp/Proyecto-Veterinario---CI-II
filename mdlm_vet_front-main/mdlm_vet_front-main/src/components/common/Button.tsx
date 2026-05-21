'use client';

import { ButtonHTMLAttributes, ReactNode } from 'react';

interface ButtonProps extends ButtonHTMLAttributes<HTMLButtonElement> {
  children: ReactNode;
  /** Variante visual del botón */
  variant?: 'primary' | 'outline';
  /** Ancho completo */
  fullWidth?: boolean;
}

export default function Button({
  children,
  variant = 'primary',
  fullWidth = false,
  className = '',
  ...props
}: ButtonProps) {
  const base =
    'font-semibold py-3.5 px-10 rounded-full transition duration-300 active:scale-95 cursor-pointer text-center';

  const variants: Record<string, string> = {
    primary:
      'bg-[#2ecc71] hover:bg-[#27ae60] text-white shadow-lg hover:shadow-xl hover:shadow-[#2ecc71]/30',
    outline:
      'bg-white/10 hover:bg-white/20 text-white border-2 border-white/30 backdrop-blur-sm hover:border-white/50',
  };

  const width = fullWidth ? 'w-full' : '';

  return (
    <button
      className={`${base} ${variants[variant]} ${width} ${className}`}
      {...props}
    >
      {children}
    </button>
  );
}
