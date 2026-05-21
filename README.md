# FunShirt — Estado do Projeto & Handoff

> Loja online de t-shirts estampadas — projeto de Aplicações para a Internet (EI, 2.º ano).
> Documento de apoio à equipa. Última atualização: **22/05/2026**. Entrega: **13/06/2026**.

---

# PARTE 1 — PONTO DE SITUAÇÃO

## Visão geral

| G | Grupo de funcionalidades | Peso | Estado |
|---|--------------------------|------|--------|
| G1 | Autenticação, Perfil e Gestão de Utilizadores | 20% | ✅ **100%** |
| G2 | Catálogo | 20% | 🔴 ~30% |
| G3 | Carrinho de compras | 20% | 🟠 ~40% |
| G4 | Encomendas | 20% | 🟠 ~45% |
| G5 | Imagens personalizadas | 5% | 🔴 ~30% |
| G6 | Recibos e email | 5% | ✅ **100%** |
| G7 | Preview de t-shirts (opcional) | 5% | ⚪ ~5% |
| G8 | Estatísticas | 5% | ✅ **100%** |

**Projeto global: ~55%.** Grupos fechados: G1, G6, G8 (30% do peso total).

## Detalhe por grupo

### ✅ G1 — Autenticação, Perfil e Gestão de Utilizadores (FEITO)
- Login/logout, recuperação de password, verificação de email no registo.
- Registo de cliente com nome, email, género e password.
- Perfil do cliente/admin: nome, email, género, foto; secção de faturação (NIF, morada, pagamento) só para clientes.
- Funcionários só alteram a password (sem acesso ao perfil).
- Painel admin: CRUD de funcionários/admins, listar/filtrar/remover clientes, bloquear/desbloquear contas.
- `UserPolicy`, soft delete, login bloqueado para contas `blocked`.

### ✅ G6 — Recibos e Email (FEITO)
- PDF do recibo gerado ao fechar a encomenda + guardado no servidor.
- Emails de encomenda pendente / fechada (com recibo anexo) / anulada, via Mailtrap.
- Download do recibo com acesso restrito (só cliente dono + admin) — `OrderPolicy`, `ReceiptController`.

### ✅ G8 — Estatísticas (FEITO)
- Painel `/painel` com faturação total/média/mês/ano, t-shirts vendidas, encomendas por estado, gráfico de 12 meses (CSS), top 5 imagens/categorias/clientes, últimas encomendas.

### 🔴 G2 — Catálogo (~30%) — FALTA MUITO
- ✅ Feito: ver catálogo público, filtro por categoria.
- ❌ Falta **toda a gestão de admin** (não existe controller): CRUD de categorias, CRUD de imagens do catálogo, gestão de cores, **configuração de preços** — tudo com upload de ficheiros.
- ❌ Pesquisa só procura no nome (falta descrição); sem paginação.

### 🟠 G3 — Carrinho (~40%)
- ✅ Feito: carrinho na sessão, adicionar/remover, total, descontos por quantidade.
- ❌ **Não se escolhe cor nem tamanho ao adicionar** (requisito central).
- ❌ Não se altera cor/tamanho/quantidade de uma linha; falta "limpar carrinho".

### 🟠 G4 — Encomendas (~45%)
- ✅ Feito: criar encomenda no checkout, transições pending→closed/canceled, PDF, emails.
- ❌ **Falta a integração com a plataforma de pagamentos** (secção 7 do enunciado — chamada HTTP à API externa). Parte obrigatória que não existe.
- 🐛 **Bug:** `CheckoutController` usa `$customerId = 22` fixo para não-autenticados.
- ❌ Checkout não é exclusivo de clientes; não redireciona anónimos para login.
- ❌ Falta: campo `notes`, `reason_for_cancellation`, pré-preenchimento com o perfil, histórico de encomendas do próprio cliente, filtro por cliente.
- ❌ Rotas de encomendas sem middleware de auth/autorização.

### 🔴 G5 — Imagens personalizadas (~30%)
- ✅ Feito: upload de imagem → carrinho.
- ❌ Imagens em pasta **pública** (o enunciado exige privadas).
- ❌ Não existe a área de gestão das imagens do cliente (consultar/editar/apagar).

### ⚪ G7 — Preview de t-shirts (~5%)
- Opcional, não crítico. Praticamente por fazer.

## Requisitos transversais (contam para a nota de TODOS os grupos)
- 🔴 **Segurança** — rotas de encomendas/carrinho/checkout/catálogo sem middleware. Ponto mais fraco.
- 🟠 **Form Requests / Policies** — só usados em G1/G6; o resto valida "à mão".
- 🟠 **Performance** — `->get()` sem paginação em vários sítios (catálogo ~277 t-shirts numa página).

## Entregáveis (não-código, secção 8 do enunciado)
- ZIP do código (sem `vendor`, `node_modules`, `database`, `storage`, `.git`).
- Relatório do grupo (Excel) — 1 pessoa submete.
- Relatórios individuais (Excel) — cada um submete o seu.
- Link externo com o ZIP completo (no relatório do grupo, ativo ≥ 1 mês).

## Prioridades sugeridas
1. **G4** — integração de pagamentos + corrigir bug `customerId=22`.
2. **G2** — gestão de admin do catálogo (vale 20%).
3. **G3** — escolher cor/tamanho ao adicionar ao carrinho.
4. **Segurança** das rotas (transversal — afeta a nota de tudo).

## Como correr o projeto localmente
- Ambiente: Laragon (Windows), PHP 8.4+, Node 24.
- Terminal 1: `php artisan serve` · Terminal 2: `npm run dev`
- Aceder em **http://localhost:8000**.
- Base de dados SQLite. Todos os utilizadores de teste têm password `123`
  (admins `a1/a2/a3@mail.pt`, funcionários `f1/f2/f3@mail.pt`, clientes `c1..c10@mail.pt`).

---

# PARTE 2 — PROMPT PARA DAR À IA

> Copiar tudo o que está dentro do bloco abaixo e colar no início de uma sessão
> com a IA (Gemini/Claude/etc.) antes de pedir trabalho em G2/G3/G4/G5.

```
Estás a ajudar um grupo de 3 alunos num projeto de faculdade: a loja online
"FunShirt" (t-shirts estampadas), em Laravel. Lê todo este contexto antes de
escrever código.

== REGRAS DE TRABALHO (obrigatórias) ==
- NÃO usar JavaScript para lógica. Permitido só: Livewire e JS residual para
  pequenos efeitos de UI (o Breeze já traz Alpine.js — não acrescentar JS novo).
- NÃO alterar a base de dados. A migração 2026_03_26_155238_initial.php é
  fornecida pelo docente e é fixa.
- Trabalhar de forma simples, sem inventar demasiado.
- Boas práticas Laravel: MVC, Eloquent (nunca o facade DB), Form Requests para
  validação, Policies para autorização, Hash/auth nativos, rotas e métodos HTTP
  corretos, DRY (partials, componentes Blade), performance (paginar, select()
  só do necessário, eager loading).
- Interface em Português. UI em Tailwind, reutilizando os componentes Breeze
  (x-text-input, x-input-label, x-input-error, x-primary-button).

== STACK ==
- Laravel 13 + SQLite + Breeze (Blade + Alpine). PHP 8.4 (Laragon), Node 24.
- Correr: php artisan serve + npm run dev -> http://localhost:8000.
- Pacote barryvdh/laravel-dompdf instalado (PDFs).
- Todos os utilizadores de teste têm password "123". Emails de teste:
  admins a1/a2/a3@mail.pt, funcionários f1/f2/f3@mail.pt,
  clientes c1..c10@mail.pt.

== FACTOS TÉCNICOS (todas as IAs têm de saber) ==
- user_type: C (Cliente), F (Funcionário), A (Administrador). NÃO existe "E".
- gender: M ou F, NOT NULL.
- customers é subclasse de users: mesma PK (não autoincremental). Criar User
  primeiro, depois Customer com o id gerado; remover pela ordem inversa.
- User e Customer usam o trait SoftDeletes.
- Relações: $user->customer e $customer->user.
- Helpers no User: isAdmin(), isEmployee(), isCustomer(), photoLink().
- Password do User tem cast 'hashed'.
- O Controller base usa AuthorizesRequests ($this->authorize(...) funciona).
- Middleware alias: 'admin' -> App\Http\Middleware\IsAdmin.
- Policies existentes (auto-discovered): UserPolicy, OrderPolicy.
- A navbar está em resources/views/layouts/app.blade.php (role-aware).
  layouts/navigation.blade.php NÃO é usado.
- Flash: a área de admin usa session('success'); o perfil usa session('status')
  com códigos. Não misturar.
- Storage: fotos em storage/app/public/photos (campo photo_url guarda só o nome
  do ficheiro). Recibos em storage/app/private/pdf_receipts (disco 'local').
- Email verification está ativo (User implements MustVerifyEmail); staff criado
  pelo admin é pré-verificado.
- Pagamentos simulados: API em https://ainet-payments-api.vercel.app
  (POST /api/payments), consumir com o Laravel HTTP Client.
- Emails via mailtrap.io.

== ESTADO DOS GRUPOS ==
FEITOS (100%): G1 (auth/perfil/utilizadores), G6 (recibos/email),
G8 (estatísticas).
POR FAZER:
- G2 Catálogo: falta toda a gestão de admin (CRUD de categorias, imagens,
  cores e preços, com upload). Pesquisa só procura no nome; falta paginação.
- G3 Carrinho: falta escolher cor/tamanho ao adicionar; editar itens;
  limpar carrinho.
- G4 Encomendas: falta a integração com a plataforma de pagamentos (secção 7
  do enunciado). BUG: CheckoutController tem $customerId=22 fixo. Checkout
  não é exclusivo de clientes; faltam notes, reason_for_cancellation,
  pré-preenchimento, histórico do cliente. Rotas sem middleware.
- G5 Imagens personalizadas: imagens estão em pasta pública (deviam ser
  privadas); falta a área de gestão das imagens do cliente.
- G7 Preview de t-shirts: opcional, não crítico.
TRANSVERSAL: muitas rotas (encomendas, carrinho, checkout, catálogo) não têm
middleware de auth/autorização — corrigir, mas o carrinho tem de continuar a
funcionar para anónimos.

== COMO TRABALHAR ==
- Antes de mexer em código de outro grupo de funcionalidades, avisar o colega
  responsável.
- Verificar sempre: php artisan route:list, php artisan view:cache (apanha
  erros de Blade) e testar no browser.
- ZIP de entrega exclui vendor, node_modules, database, storage e .git.
```
