// @types/js-cookie.d.ts
declare module 'js-cookie' {
    interface CookieOptions {
      expires?: number | Date;
      path?: string;
      domain?: string;
      secure?: boolean;
      sameSite?: 'lax' | 'strict' | 'none';
    }
  
    export function set(name: string, value: string, options?: CookieOptions): void;
    export function get(name: string): string | undefined;
    export function remove(name: string, options?: CookieOptions): void;
  }
  